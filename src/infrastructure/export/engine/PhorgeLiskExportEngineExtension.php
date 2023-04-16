<?php

final class PhorgeLiskExportEngineExtension
  extends PhorgeExportEngineExtension {

  const EXTENSIONKEY = 'lisk';

  public function supportsObject($object) {
    if (!($object instanceof LiskDAO)) {
      return false;
    }

    if (!$object->getConfigOption(LiskDAO::CONFIG_TIMESTAMPS)) {
      return false;
    }

    return true;
  }

  public function newExportFields() {
    return array(
      id(new PhorgeEpochExportField())
        ->setKey('dateCreated')
        ->setLabel(pht('Created')),
      id(new PhorgeEpochExportField())
        ->setKey('dateModified')
        ->setLabel(pht('Modified')),
    );
  }

  public function newExportData(array $objects) {
    $map = array();
    foreach ($objects as $object) {
      $map[] = array(
        'dateCreated' => $object->getDateCreated(),
        'dateModified' => $object->getDateModified(),
      );
    }
    return $map;
  }

}
