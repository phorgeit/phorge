<?php

final class PhorgeMailPropertiesDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'mail.properties';

  public function getExtensionName() {
    return pht('Mail Properties');
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    $object_phid = $object->getPHID();
    $viewer = $engine->getViewer();

    $properties = id(new PhorgeMetaMTAMailPropertiesQuery())
      ->setViewer($viewer)
      ->withObjectPHIDs(array($object_phid))
      ->executeOne();
    if ($properties) {
      $properties->delete();
    }
  }

}
