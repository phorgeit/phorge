<?php

abstract class PhameSite extends PhorgeSite {

  protected function isPhameActive() {
    $base_uri = PhorgeEnv::getEnvConfig('phorge.base-uri');
    if (!strlen($base_uri)) {
      // Don't activate Phame if we don't have a base URI configured.
      return false;
    }

    $phame_installed = PhorgeApplication::isClassInstalled(
      'PhorgePhameApplication');
    if (!$phame_installed) {
      // Don't activate Phame if the the application is uninstalled.
      return false;
    }

    return true;
  }

}
