<?php

final class ExtensionInstallFromStore extends PhorgeExtensionInstallerStrategy {

  private $extensionStores;

  public function setExtensionStores($stores) {
    $this->extensionStores = $stores;
    return $this;
  }

  private function findInStores(string $extension_key) {
    $console = PhutilConsole::getConsole();

    $extension_data = null;
    foreach ($this->extensionStores as $store) {
      $console->writeLog(
        "Looking for %s in store %s\n",
        $extension_key,
        $store['uri']);
      $client = new ExtensionStoreClient($store['uri']);
      $extension_data = $client->queryExtension($extension_key);

      if ($extension_data) {
        $console->writeLog(
          "%s\n",
          pht(
            'Extension %s found in store %s.',
            $extension_key,
            $store['uri']));
        break;
      }
    }

    if (!$extension_data) {
      throw new ArcanistUsageException(
        pht('Extension with key %s not found in any store.', $extension_key));
    }

    return $this->prepareFromExtensionQueryResult($extension_data);
  }

  private function prepareFromExtensionQueryResult(
    ExtensionQueryResult $extension_data) {

    if ($extension_data->getFormat() != 'phar-file') {
      throw new ArcanistUsageException(
        pht(
          "Unknown extension format `%s` - maybe upgrade %s first?\n",
          $extension_data->getFormat(),
          PlatformSymbols::getPlatformServerName()));
    }

    $basename = sprintf(
      '%s-%s.%s',
      $extension_data->getExtensionKey(),
      $extension_data->getVersion(),
      'phar');

    if (!preg_match('/^[\w_\d\\.-]*\.phar$/', $basename)) {
      throw new ArcanistUsageException(pht('Invalid filename `%s`', $basename));
    }

    return array($extension_data, $basename);
  }

  private function prepare($source) {
    if ($source instanceof ExtensionQueryResult) {
      // For the upgrade flow
      return $this->prepareFromExtensionQueryResult($source);
    }

    return $this->findInStores($source);
  }


  protected function fetchContent($source): string {
    $console = PhutilConsole::getConsole();

    list($extension_data, $basename) = $this->prepare($source);
    $download_uri = $extension_data->getDownloadUri();

    if ($this->isDryRun()) {
      $console->writeOut(
        pht(
          "Would download and install '%s' as '%s'\n",
          $download_uri,
          $basename));
      throw new ArcanistUsageException(pht('Dry-run.'));
    }

    $temp_dir = Filesystem::createTemporaryDirectory();

    try {
      $local_filename = Filesystem::resolvePath($basename, $temp_dir);

      // TODO check sigs

      // TODO check if already installed, if so maybe upgrade


      $console->writeOut(
        pht("Downloading %s to %s\n", $download_uri, $local_filename));

      $future = id(new HTTPSFuture($download_uri))
        ->setDownloadPath($local_filename);
      // TODO there's a progress-bar feature, but it needs to know total size.

      $future->resolvex();

      $this->sanityCheckLibrary($extension_data, $local_filename);

      $next_installer = id(new ExtensionInstallPhar())
        ->setDryRun($this->isDryRun());

      $install_location = $next_installer->fetchContent($local_filename);

      $record = $this->produceDbRecord($extension_data, $install_location);

      $storage = PhorgeExtensionsLocalData::load();
      $storage->setRecordInMap(
        'extensions-from-store',
        $install_location,
        $record);
      $storage->save();

      return $install_location;

    } finally {
      try {
        Filesystem::remove($temp_dir);
      } catch (Throwable $e) {
        // Ignore.
      }
    }
  }

  private function produceDbRecord($extension_data, $location) {

    return array(
      'format' => 'store-phar',
      'install-dir' => $location,
      'version' => $extension_data->getVersion(),
      'extension-key' => $extension_data->getExtensionKey(),
      'store-uri' => $extension_data->getStoreUri(),
   );
  }

  private function sanityCheckLibrary(
    ExtensionQueryResult $extension_data,
    string $local_filename) {

    $console = PhutilConsole::getConsole();
    $warnings = array();

    try {
      $init_filename = Filesystem::resolvePath(
        'src/__phutil_library_init__.php',
        'phar://'.$local_filename);

      $init_file_content = Filesystem::readFile($init_filename);

      $register_lib_regex =
        '/^\s*phutil_register_library\([\'"]([\w_-]*)[\'"]/m';

      $matches = null;
      if (preg_match($register_lib_regex, $init_file_content, $matches)) {

        $actual_phutil_name = $matches[1];
        $expected_phutil_name = $extension_data->getPhutilLibName();

        if ($actual_phutil_name !== $expected_phutil_name) {
          $warnings[] = pht(
            'The package is listed in the store as library `%s`, but the '.
            'actual library downloaded is named `%s`. Depending on context, '.
            'this may or may not be important.',
            $expected_phutil_name,
            $actual_phutil_name);
        }
      } else {
        $warnings[] = pht(
        "Unable to read the %s file of the downloaded package - can't ".
        'decide what the name the library is using.',
        'src/__phutil_library_init__.php');
      }

    } catch (Throwable $ex) {

      array_unshift(
        $warnings,
        pht(
          'Encountered an error trying to verify the downloaded package: %s',
          $ex));
    }

    if ($warnings) {
      $console->writeOut("\n");
      $text = id(new PhutilConsoleBlock())
        ->addParagraph(pht('Had issues verifying this download:'));

      foreach ($warnings as $warning) {
        $text->addParagraph($warning);
      }
      $text->draw();

      $continue = $console->confirm(
        pht('Ignore these issues and continue with installation?'));
      if (!$continue) {
        $console->writeOut(pht('Aborting.'."\n"));
        throw new ArcanistUserAbortException();
      }
    }
  }

}
