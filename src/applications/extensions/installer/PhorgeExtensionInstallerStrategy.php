<?php

abstract class PhorgeExtensionInstallerStrategy extends Phobject {

  private $dryRun = false;

  public function setDryRun($dry_run) {
    $this->dryRun = $dry_run;
    return $this;
  }

  public function isDryRun() {
    return $this->dryRun;
  }

  final public function install($source) {

    $location = $this->fetchContent($source);

    id(new PhorgeExtensionsManageLoadLibraries())
      ->setDryRun($this->isDryRun())
      ->addToLoadLibraries($location);
  }

  /**
   * Throw if you can't do it.
   * @return string path of where the library was downloaded to (or would be if
   * dry-run).
   */
  abstract protected function fetchContent($source): string;

  public function getInstallDir() {
    $install_dir = PhabricatorEnv::getEnvConfig('extensions.install-dir');
    Filesystem::createDirectory($install_dir);
    return $install_dir;
  }

  public static function getAllInstallers() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->execute();
  }

}
