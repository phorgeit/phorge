<?php

final class PhorgeUpgradeExtensionsWorkflow
  extends PhorgeExtensionsManagementWorkflow {

  private $dryRun = false;

  protected function didConstruct() {
    $this
      ->setName('upgrade')
      ->setSynopsis('Upgrade installed extensions')
      ->setExamples(
        array(
          '**upgrade** --all',
          '**upgrade** __library_name__',
        ))
      ->setArguments(
        array(
          id(new PhutilArgumentSpecification())
            ->setName('argv')
            ->setWildcard(true),
          id(new PhutilArgumentSpecification())
            ->setName('all')
            ->setHelp(pht('Try to update all installed extensions.')),
          id(new PhutilArgumentSpecification())
            ->setName('dry-run'),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $console = PhutilConsole::getConsole();
    $argv = $args->getArg('argv');

    $this->dryRun = $args->getArg('dry-run');

    $query = new PhorgeLibraryQuery();

    if (!$args->getArg('all')) {
      $query->withNames($argv);
    }

    $to_update = $query->execute();

    foreach ($to_update as $lib) {
      if ($lib->isCoreLibrary()) {
        $console->writeOut(
          "%s\n",
          pht('Skipping core library `%s`.', $lib->getName()));
        continue;
      }

      $format = $this->findLibraryFormat($lib);
      switch ($format) {
        case 'git':
          $this->upgradeExtensionFromGit($lib);
          break;

        case 'store-phar':
          $this->upgradeExtensionFromStore($lib);
          break;

        default:
          $console->writeOut(
            "%s\n",
            pht(
              'Upgrading extensions of format "%s" is not supported.',
              $format));
          break;
      }

    }

    return 3;
  }

  private function upgradeExtensionFromStore(
    PhorgeLibraryMetadata $old_extension) {

    // TODO batch the store queries.

    $install_data = PhorgeExtensionsLocalData::load()
      ->findRecordInMap('extensions-from-store', $old_extension->getLocation());

    $store_uri = idx($install_data, 'store-uri');
    $extension_key = idx($install_data, 'extension-key');
    $installed_version = idx($install_data, 'version');

    $console = PhutilConsole::getConsole();
    if (!$store_uri || !$extension_key) {
      $console->writeOut(
        "%s\n",
        pht(
          'The installation record for the extension `%s` is '.
          'missing or broken - unable to upgrade.',
          $old_extension->getName()));
      return;
    }

    $client = new ExtensionStoreClient($store_uri);
    $new_extension = $client->queryExtension($extension_key);
    if (!$new_extension) {
      $console->writeOut(
        "%s\n",
        pht(
          "Extension %s not found in store %s - maybe it was deleted?. '.
          'Unable to upgrade.",
          $extension_key,
          $store_uri));
      return;
    }

    $latest_version = $new_extension->getVersion();
    if (version_compare($latest_version, $installed_version, '<=')) {
      $console->writeOut(
        "%s\n",
        pht('Extension %s is already up-to-date', $old_extension->getName()));
      return;
    }

    $console->writeOut(
      "%s\n",
      pht(
        'Upgrading extension %s ("%s"). '.
        'Currently installed %s, upgrading to %s. '.
        'Downloading from store %s',
        $new_extension->getExtensionKey(),
        $new_extension->getPhutilLibName(),
        $installed_version,
        $latest_version,
        $store_uri));

    $installer = id(new ExtensionInstallFromStore())
      ->setDryRun($this->dryRun);

    $installer->install($new_extension);

    id(new PhorgeExtensionsManageLoadLibraries())
      ->setDryRun($this->dryRun)
      ->removeFromLoadLibraries($old_extension->getLocation());
  }

  private function upgradeExtensionFromGit(PhorgeLibraryMetadata $library) {
    $console = PhutilConsole::getConsole();
    $workdir = $library->getLocation();

    list($installed_version) =
      id(new ExecFuture('git log --format=%s -n 1 --', '%H'))
      ->setCWD($workdir)
      ->resolvex();
    $installed_version = trim($installed_version);

    $console->writeOut(
      "%s\n",
      pht(
        'Upgrading extension "%s". '.
        'Current version-hash %s.',
        $library->getName(),
        $installed_version));


    $future = id(new ExecFuture('git pull --ff-only --'))
      ->setCWD($workdir);

    if ($this->dryRun) {
      $console->writeOut(
        pht(
          "Would run: `%s` at %s\n",
          $future->getCommand(),
          $future->getCWD()));
      return;
    }

    try {
      $future->resolvex();
    } catch (Throwable $ex) {
      $console->writeOut(
        "%s\n%s\n",
        pht(
          'Failed to upgrade git library. Try fixing manually. Error: %s',
          $ex->getMessage()),
        pht(
          'Command was `%s` at `%s`',
          $future->getCommand(),
          $future->getCWD()));

      return;
    }

    list($new_version) =
      id(new ExecFuture('git log --format=%s -n 1 --', '%H'))
      ->setCWD($workdir)
      ->resolvex();
    $new_version = trim($new_version);

    if ($new_version == $installed_version) {
      $message = pht('Library was already at latest version.');
    } else {
      $message = pht(
        'Update complete; Current version %s. '.
        'Remember to run `%s` and restart the server!',
        $new_version,
        'bin/storage upgrade');
    }

    $console->writeOut("%s\n", $message);
  }

}
