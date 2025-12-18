<?php

abstract class PhabricatorMailExternalMessage
  extends Phobject {

  final public function getMessageType() {
    return $this->getPhobjectClassConstant('MESSAGETYPE');
  }

  final public static function getAllMessageTypes() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(self::class)
      ->setUniqueMethod('getMessageType')
      ->execute();
  }

}
