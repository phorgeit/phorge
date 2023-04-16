<?php

abstract class PhorgeAuthTemporaryTokenType
  extends Phobject {

  abstract public function getTokenTypeDisplayName();
  abstract public function getTokenReadableTypeName(
    PhorgeAuthTemporaryToken $token);

  public function isTokenRevocable(PhorgeAuthTemporaryToken $token) {
    return false;
  }

  final public function getTokenTypeConstant() {
    return $this->getPhobjectClassConstant('TOKENTYPE', 64);
  }

  final public static function getAllTypes() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getTokenTypeConstant')
      ->execute();
  }

}
