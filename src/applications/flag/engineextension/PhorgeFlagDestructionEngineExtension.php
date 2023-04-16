<?php

final class PhorgeFlagDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'flags';

  public function getExtensionName() {
    return pht('Flags');
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    $object_phid = $object->getPHID();

    if ($object instanceof PhorgeFlaggableInterface) {
      $flags = id(new PhorgeFlag())->loadAllWhere(
        'objectPHID = %s',
        $object_phid);
      foreach ($flags as $flag) {
        $flag->delete();
      }
    }

    $flags = id(new PhorgeFlag())->loadAllWhere(
      'ownerPHID = %s',
      $object_phid);
    foreach ($flags as $flag) {
      $flag->delete();
    }
  }

}
