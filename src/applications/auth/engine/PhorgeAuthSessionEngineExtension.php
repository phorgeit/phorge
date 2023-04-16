<?php

abstract class PhorgeAuthSessionEngineExtension
  extends Phobject {

  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getExtensionKey')
      ->execute();
  }

  abstract public function getExtensionName();

  public function didEstablishSession(PhorgeAuthSessionInfo $info) {
    return;
  }

  public function willServeRequestForUser(PhorgeUser $user) {
    return;
  }

  public function didLogout(PhorgeUser $user, array $sessions) {
    return;
  }

}
