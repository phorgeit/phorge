<?php

abstract class PhorgeExtensionsManagementWorkflow
  extends PhabricatorManagementWorkflow {

  protected function isExtensionKey($input) {
    if (preg_match('/^[a-z][a-z0-9-]*\.[a-z][a-z0-9-]+\z/', $input)) {
      $console = PhutilConsole::getConsole();
      $console->writeLog(
        pht("Input `%s` looks like an extension key\n", $input));
      return true;
    }

    return null;
  }

  /**
   * @return string
   */
  protected function findLibraryFormat(PhorgeLibraryMetadata $library) {
    $root = $library->getLocation();

    if (Filesystem::isPharPath($root)) {
      $install_data = PhorgeExtensionsLocalData::load()
        ->findRecordInMap('extensions-from-store', $root);
      if ($install_data) {
        return idx($install_data, 'format', 'unknown');
      }

      return 'phar';
    }

    // TODO actually check that it's a git repo.
    return 'git';
  }

  protected function getExtensionStores() {
    return PhabricatorEnv::getEnvConfig('extensions.extension-stores');
  }

  protected function getLocalConfFilename() {

    $config_source = new PhabricatorConfigLocalSource();
    return $config_source->getReadablePath();
  }


}
