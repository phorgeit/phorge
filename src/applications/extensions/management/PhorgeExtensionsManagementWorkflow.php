<?php

abstract class PhorgeExtensionsManagementWorkflow
  extends PhabricatorManagementWorkflow {

  /**
   * Returns all known extensions and all known libraries.
   * @return PhorgeLibraryMetadata[]
   */
  protected function loadAllLibrariesAndExtensions() {

    /** @var PhorgeLibraryMetadata[] */
    $libs = id(new PhorgeLibraryQuery())
      ->execute();


    return $libs;
  }

  protected function isExtensionKey($input) {
    if (preg_match('/^[a-z][a-z0-9-]*\.[a-z][a-z0-9-]+\z/', $input)) {
      $console = PhutilConsole::getConsole();
      $console->writeLog(
        pht("Input `%s` looks like an extension key\n", $input));
      return true;
    }

    return null;
  }


  protected function getExtensionStores() {
    $conf = PhabricatorEnv::getEnvConfig('extensions.extension-stores');
    if ($conf !== null) {
      return $conf;
    }

    return array(
      array(
        'name' => 'Phorge',
        'uri' => 'https://extensions.phorge.it/',
      ),
    );
  }

  protected function getLocalConfFilename() {

    $config_source = new PhabricatorConfigLocalSource();
    return $config_source->getReadablePath();
  }

  protected static function assertCanUsePhar() {
    static $good = null;

    if ($good === null) {
      $min_version = '8.0';
      $cur_version = phpversion();
      if (version_compare($cur_version, $min_version, '<')) {
        $good = false;
      } else {
        $good = true;
      }
    }
    if ($good) {
      return;
    }
    throw new Exception(
      pht(
        'PHP versions older then %s have known security vulnerabilities '.
        'when considering PHAR files; Installing extensions from PHAR files '.
        'and from the Store is therefore disabled. See %s',
        $min_version,
        'https://wiki.php.net/rfc/phar_stop_autoloading_metadata'));
  }

}
