<?php

final class PhorgePasswordDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'passwords';

  public function getExtensionName() {
    return pht('Passwords');
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    $viewer = $engine->getViewer();
    $object_phid = $object->getPHID();

    $passwords = id(new PhorgeAuthPasswordQuery())
      ->setViewer($viewer)
      ->withObjectPHIDs(array($object_phid))
      ->execute();

    foreach ($passwords as $password) {
      $engine->destroyObject($password);
    }
  }

}
