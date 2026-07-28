<?php

final class PhorgeInstallExtensionsWorkflow
  extends PhorgeExtensionsManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('install')
      ->setSynopsis(pht('Install new extensions.'))
      ->setExamples(
        array(
          '**install** [__uri__]',
          '**install** --from-phar __uri__',
        ))
      ->setArguments(
        array(
          array(
            'name' => 'from-git',
            'help' => pht('Install extension by specifying its git URI.'),
          ),
          id(new PhutilArgumentSpecification())
            ->setName('from-phar')
            ->setHelp(pht('Install a phar extension'))
            ->setConflicts(array('from-git' => null)),
          id(new PhutilArgumentSpecification())
            ->setName('store-uri')
            ->setParamName('store_uri')
            ->setHelp(
              pht(
                'Store to search in and download from '.
                '(Ignoring configuration).')),
          id(new PhutilArgumentSpecification())
            ->setName('dry-run'),
          id(new PhutilArgumentSpecification())
            ->setName('uri')
            ->setWildcard(true),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $dry_run = $args->getArg('dry-run');

    $uris = $args->getArg('uri');
    if (!$uris) {
      throw new PhutilArgumentUsageException(
        pht('Provide a uri or extension id to install'));
    } else if (count($uris) > 1) {
      throw new PhutilArgumentUsageException(
        pht('Can only install one extension at a time.'));
    }

    $uri = head($uris);

    $from_git = $args->getArg('from-git');

    if ($from_git) {
      $this->installByGitClone($uri, $dry_run);
      return;
    }


    $from_phar = $args->getArg('from-phar');
    if ($from_phar) {
      $this->assertCanUsePhar();
      $this->installPhar($uri, $dry_run);
      return;
    }

    if ($this->isExtensionKey($uri)) {
      // look for it in stores
      $this->installFromStore($uri, $dry_run);
      return;
    }

    /* TODO:
    if ($uri is something like 'https://store-url/extension-key') {
      list(store_uri, extension_key) = extract_store_and_key($uri);
      $this->installFromSpecificStore($store, $extension_key, $dry_run);
      return;
    }
    */


    throw new PhutilArgumentUsageException('TBD');




    return;

    // git-clone from source
    // add to database
    // add to conf.local

  }

  private function installFromStore(string $extension_key, $dry_run) {
    $this->assertCanUsePhar();
    $console = PhutilConsole::getConsole();
    foreach ($this->getExtensionStores() as $store) {
      $console->writeLog(
        "Looking for %s in store %s\n",
        $extension_key,
        $store['uri']);
      $client = new ExtensionStoreClient($store['uri']);
      $extension_data = $client->queryExtension($extension_key);

      if (!$extension_data) {
        $console->writeLog(
          pht(
            "Extension %s found in store %s.\n",
            $extension_key,
            $store['uri']));
        break;
      }

      continue;
    }

    if (!$extension_data) {
      $console->writeErr(
        pht("Extension with key %s not found in any store.\n", $extension_key));
      return;
    }

    if ($extension_data->getFormat() != 'phar-file') {
      $console->writeErr(
        pht(
          "Unknown extension format `%s` - maybe upgrade %s first?\n",
          $extension_data->getFormat(),
          PlatformSymbols::getPlatformServerName()));
      return;
    }

    $download_uri = $extension_data->getDownloadUri();

    $basename = sprintf(
      '%s-%s.%s',
      $extension_data->getExtensionKey(),
      $extension_data->getVersion(),
      'phar');

    if (!preg_match('/^[\w_\d\\.-]*\\.phar$/', $basename)) {
      $console->writeErr(pht("Invalid filename '%s'\n", $basename));
      return;
    }

    if ($dry_run) {
      $console->writeOut(
        pht(
          "Would download and install '%s' as '%s'\n",
          $download_uri,
          $basename));
      return;
    }

    $temp_dir = Filesystem::createTemporaryDirectory();

    try {
      $local_filename = Filesystem::resolvePath($basename, $temp_dir);

      // TODO check sigs

      // TODO check if already installed, if so maybe upgrade

      id(new PhutilConsoleBlock())
        ->setConsole($console)
        ->addParagraph(pht('This command will:'))
        ->addParagraph(
          pht(
            '1. Download the extension archive from %s to %s',
            $download_uri,
            id(new ExtensionInstallPhar())->getInstallDir()))
        ->addParagraph(
          pht(
            '2. Add the extension to `load-libraries` in %s,'.
            ' so it will be loaded into %s',
            $this->getLocalConfFilename(),
            PlatformSymbols::getPlatformServerName()))
      ->addParagraph(
        pht(
          'After running, you should run `%s` and restart the server.',
          'bin/storage upgrade'))
      ->addParagraph(
        pht(
          "Warning: We don't check what you're downloading. ".
          'It can be anything. '.
          'It has read/write access to anything %s has access to. '.
          'be sure you trust it.',
          PlatformSymbols::getPlatformServerName()))
      ->draw();


      $confirm = $console->confirm(
        pht('Continue installing whatever that is?'));
      if (!$confirm) {
        $console->writeOut(pht('Aborting.'));
        return;
      }

      $console->writeOut(
        pht("Downloading %s to %s\n", $download_uri, $local_filename));

      $future = id(new HTTPSFuture($download_uri))
        ->setDownloadPath($local_filename);
      // TODO there's a progress-bar feature, but it needs to know total size.

      $future->resolvex();

      $continue = $this->sanityCheckLibrary($extension_data, $local_filename);
      if (!$continue) {
        return;
      }

      $this->installPhar($local_filename, $dry_run);

    } finally {
      try {
        Filesystem::remove($temp_dir);
      } catch (Throwable $e) {
        // Ignore.
      }
    }
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
        return;
      }
    }

    return true;
  }

  private function installPhar(string $filename, $dry_run) {
    $installer = new ExtensionInstallPhar();
    $source = $installer->prepare($filename);
    $installer->install($source);
  }

  private function installByGitClone(string $origin, $dry_run) {
    $console = PhutilConsole::getConsole();

    $target_base_dir = Filesystem::resolvePath(
      '..',
      dirname(phutil_get_library_root('phorge')));


    $url = new PhutilURI($origin);
    // $src->isGitURI();

    $target_dir = $target_base_dir.'/TODO /';

    $text = id(new PhutilConsoleBlock())
      ->addParagraph(pht('This command will:'))
      ->addParagraph(
        pht(
          '1. `git clone` the extension from url `%s` to location `%s`',
          $url,
         $target_dir))
      ->addParagraph(
        pht(
          '2. Add the extension to `load-libraries` in %s,'.
          ' so it will be loaded into %s',
          $this->getLocalConfFilename(),
          'TODO'))
      ->addParagraph(
        pht(
          'After running, you should run `%s` and restart the server.',
          'bin/storage upgrade'))
      ->addParagraph(
        pht(
          "Warning: We don't check what you're downloading. ".
          'It can be anything. '.
          'It has read/write access to anything %s has access to. '.
          'be sure you trust it.',
          PlatformSymbols::getPlatformServerName()))
      ->drawConsoleString();

    $console->writeOut($text);

    $confirm = $console->confirm(pht('Continue installing whatever that is?'));
    if (!$confirm) {
      $console->writeOut(pht('Aborting.'));
      return;
    }

    $installer = new ExtensionInstallGit();
    $source = $installer->prepare($url);
    $installer->install($source);
  }

}
