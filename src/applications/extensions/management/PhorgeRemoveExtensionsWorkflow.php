<?php

final class PhorgeRemoveExtensionsWorkflow
  extends PhorgeExtensionsManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('remove')
      ->setSynopsis(pht('Remove an installed extensions.'))
      ->setExamples(
        array(
          '**remove** __extension_name__',
          '**remove** __extension_key__',
          '**remove** --by-location __path__',
        ))
      ->setArguments(
        array(
          id(new PhutilArgumentSpecification())
            ->setName('by-name')
            ->setHelp(pht('Identify the extension by its extension name')),
          id(new PhutilArgumentSpecification())
            ->setName('by-key')
            ->setHelp(pht('Identify the extension by its Extension Key'))
            ->setConflicts(array('by-name' => null)),
          id(new PhutilArgumentSpecification())
            ->setName('by-location')
            ->setHelp(pht('Identify the extension by its local path'))
            ->setConflicts(array('by-name' => null, 'by-key' => null)),
          id(new PhutilArgumentSpecification())
            ->setName('store-uri')
            ->setParamName('store_uri')
            ->setHelp(
              pht(
                'Store to query when removing by Key '.
                '(Ignoring configuration).'))
            ->setConflicts(array('by-name' => null, 'by-location' => null)),
          id(new PhutilArgumentSpecification())
            ->setName('dry-run'),
          id(new PhutilArgumentSpecification())
            ->setName('spec')
            ->setWildcard(true),
        ));
  }

  public function execute(PhutilArgumentParser $args) {
    $dry_run = $args->getArg('dry-run');

    $names = $args->getArg('spec');
    if (!$names) {
      throw new PhutilArgumentUsageException(
        pht('Provide a name or extension id to remove'));
    } else if (count($names) > 1) {
      throw new PhutilArgumentUsageException(
        pht('Can only remove one extension at a time.'));
    }

    $name = head($names);

    $identifier_type = null;

    if ($args->getArg('by-name')) {
      $identifier_type = 'name';
    } else if ($args->getArg('by-location')) {
      $identifier_type = 'path';
    } else if ($args->getArg('by-key') || $this->isExtensionKey($name)) {
      $identifier_type = 'key';
    }

    if (!$identifier_type) {
      if (strpos($name, '/') !== false) {
        $identifier_type = 'path';
      } else {
        $identifier_type = 'name';
      }
    }

    $extension_to_remove = $this->findExtension($identifier_type, $name);

    $location = $extension_to_remove->getLocation();
    $format = $this->findLibraryFormat($extension_to_remove);

    id(new PhorgeExtensionsManageLoadLibraries())
      ->setDryRun($dry_run)
      ->removeFromLoadLibraries($location);

    switch ($format) {
      case 'git':
        list($git_root) = id(new ExecFuture('git rev-parse --show-toplevel'))
          ->setCWD($location)
          ->resolvex();
        $git_root = trim($git_root);

        $message = pht(
          'Not deleting library from disk, as it\'s a git repository and '.
          'might have local changes. To remove, run `%s` .',
          csprintf('rm -rf %s', $git_root));
        break;

      case 'phar':
      case 'store-phar':
        $database = PhorgeExtensionsLocalData::load();
        $database->removeRecordFromMap('extensions-from-store', $location);

        $phorge_root = phutil_get_library_root('phorge');
        $location = Filesystem::resolvePath($location, $phorge_root);
        $path_to_remove = Filesystem::getPharArchivePath($location);

        if ($dry_run) {
          $message = pht('Would remove library file %s', $path_to_remove);

        } else {
          $message = pht('Removed library file %s', $path_to_remove);
          execx('rm %s', $path_to_remove);

          $database->save();
        }
        break;

      default:
        $message = pht(
          'Unknown library format `%s`, not deleting from disk. '.
          'Location was %s.',
          $format,
          $location);
        break;
    }

    // show note explaining about rest of cleanup
    $console = PhutilConsole::getConsole();
    $console->writeOut("%s\n", $message);
  }

  private function findExtension($type, $spec): PhorgeLibraryMetadata {

    $query = new PhorgeLibraryQuery();

    switch ($type) {
      case 'path':
        $query->withLocations(array($spec));
        break;

      case 'name':
        $query->withNames(array($spec));
        break;

      default:
        throw new ArcanistUsageException(
          pht('Finding extension by %s is not yet implemented.', $type));
    }

    $library = head($query->execute());
    if (!$library) {
      throw new ArcanistUsageException(
        pht('Did not find an extension with %s "%s".', $type, $spec));
    }

    return $library;
  }

}
