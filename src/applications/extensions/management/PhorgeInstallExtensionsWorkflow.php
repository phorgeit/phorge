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
          id(new PhutilArgumentSpecification())->setName('skip-confirm') ,
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
        pht('Provide a uri or extension key to install'));
    } else if (count($uris) > 1) {
      throw new PhutilArgumentUsageException(
        pht('Can only install one extension at a time.'));
    }

    $uri = head($uris);

    /** @var PhorgeExtensionInstallerStrategy|null */
    $installer = null;

    if ($args->getArg('from-git')) {
      $installer = new ExtensionInstallGit();
    }

    if ($args->getArg('from-phar')) {
      $installer = new ExtensionInstallPhar();
    }

    if ($this->isExtensionKey($uri)) {
      $installer = id(new ExtensionInstallFromStore())
        ->setExtensionStores($this->getExtensionStores());
    }

    if (!$installer) {
      throw new PhutilArgumentUsageException('failed to guess installer');
    }


    if (!$args->getArg('skip-confirm') && !$this->confirm($uri, $installer)) {
      return;
    }

    $installer
      ->setDryRun($dry_run)
      ->install($uri);

    /* TODO:
    if ($uri is something like 'https://store-url/extension-key') {
      list(store_uri, extension_key) = extract_store_and_key($uri);
      $this->installFromSpecificStore($store, $extension_key, $dry_run);
      return;
    }
    */


  }

  private function confirm($uri, $installer) {
    $console = PhutilConsole::getConsole();

    $text = id(new PhutilConsoleBlock())
      ->addParagraph(pht('This command will:'))
      ->addParagraph(
        pht(
          '1. download the extension the extension from url `%s` to location '.
          '`%s` (using %s)',
          $uri,
          $installer->getInstallDir(),
          '???'))
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
    if ($confirm) {
      return true;
    }
    $console->writeOut(pht('Aborting.'));
    return false;
  }



}
