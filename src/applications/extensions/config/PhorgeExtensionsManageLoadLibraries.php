<?php

final class PhorgeExtensionsManageLoadLibraries extends Phobject {

  private $dryRun = false;

  private static $key = 'load-libraries';

  public function setDryRun($dry_run) {
    $this->dryRun = $dry_run;
    return $this;
  }

  public function addToLoadLibraries(string $location) {

    if (!Filesystem::pathExists($location.'/__phutil_library_init__.php')) {
      throw new ArcanistUsageException(
        pht('It appears that this is not a valid library.'));
    }

    $config_source = new PhabricatorConfigLocalSource();
    $full_config = $config_source->getKeys(array(self::$key));

    $full_config[self::$key][] = $location;
    $local_path = $config_source->getReadablePath();

    $console = PhutilConsole::getConsole();
    if ($this->dryRun) {
      $console->writeOut(
        "%s\n",
        pht(
          'Would add `%s` to key `%s` in file %s',
          $location,
          self::$key,
          $local_path));
      return;
    }

    try {
      $config_source->setKeys($full_config);
    } catch (FilesystemException $ex) {
      throw new ArcanistUsageException(
        pht(
          'Local path "%s" is not writable.',
          Filesystem::readablePath($local_path)));
    }

    $console->writeOut(
      "%s\n",
      pht(
        'Wrote configuration key "%s" to local storage (in file "%s").',
        self::$key,
        $local_path));
  }


  public function removeFromLoadLibraries(string $location) {

    $phorge_root = phutil_get_library_root('phorge');
    $location = Filesystem::resolvePath($location, $phorge_root);
    $config_source = new PhabricatorConfigLocalSource();
    $full_config = $config_source->getKeys(array(self::$key));

    $value = idx($full_config, self::$key);
    if (!$value) {
      return;
    }
    foreach ($value as $index => $config_path) {
      if (Filesystem::resolvePath($config_path, $phorge_root) == $location) {
        unset($value[$index]);
      }
    }
    $value = array_values($value);

    $full_config[self::$key] = $value;
    $local_path = $config_source->getReadablePath();

    if ($this->dryRun) {
      $console = PhutilConsole::getConsole();
      $console->writeOut(
        "%s\n",
        pht(
          'Would remove `%s` from key `%s` in file %s',
          $location,
          self::$key,
          $local_path));
      return;
    }

    try {
      $config_source->setKeys($full_config);
    } catch (FilesystemException $ex) {
      throw new PhutilArgumentUsageException(
        "%s\n",
        pht(
          'Local path "%s" is not writable. This file must be writable '.
          'so that "bin/config" can store configuration.',
          Filesystem::readablePath($local_path)));
    }

    $message = pht(
      'Wrote configuration key "%s" to local storage (in file "%s").',
      self::$key,
      $local_path);

    PhutilConsole::getConsole()->writeOut("%s\n", $message);
  }

}
