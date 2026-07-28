<?php

abstract class PhorgeExtensionInstallerStrategy extends Phobject {

  private $dryRun = false;

  /**
   * @return mixed something that can be used to install?
   */
  public function prepare($source) {
    return $source;
  }

  public function setDryRun($dry_run) {
    $this->dryRun = $dry_run;
    return $this;
  }

  public function isDryRun() {
    return $this->dryRun;
  }

  abstract public function install($some_input);

  protected function addToLoadLibraries(string $location) {

    // TODO nicer error message
    Filesystem::assertExists($location.'/__phutil_library_init__.php');



    $config_source = new PhabricatorConfigLocalSource();
    $key = 'load-libraries';
    $value = $config_source->getKeys(array($key));

    $value[$key][] = $location;
    $local_path = $config_source->getReadablePath();

    if ($this->isDryRun()) {
      $console = PhutilConsole::getConsole();
      $console->writeOut(
        pht(
          "Would add `%s` to key `%s` in file %s\n",
          $location,
          $key,
          $local_path));
      return;
    }

    try {
      $config_source->setKeys($value);
    } catch (FilesystemException $ex) {
      throw new PhutilArgumentUsageException(
        pht(
          'Local path "%s" is not writable. This file must be writable '.
          'so that "bin/config" can store configuration.'.
          "\n",
          Filesystem::readablePath($local_path)));
    }

    $write_message = pht(
      'Wrote configuration key "%s" to local storage (in file "%s").'."\n",
      $key,
      $local_path);

      PhutilConsole::getConsole()->writeOut($write_message);
  }


  public function getInstallDir() {
    $config = PhabricatorEnv::getEnvConfig('extensions.install-dir');
    if ($config !== null) {
      Filesystem::createDirectory($config);
      return $config;
    }

    return Filesystem::resolvePath(
      '..',
      dirname(phutil_get_library_root('phorge')));
  }

  public static function getAllInstallers() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->execute();
  }

}
