<?php

final class PhorgeSpacesExportEngineExtension
  extends PhorgeExportEngineExtension {

  const EXTENSIONKEY = 'spaces';

  public function supportsObject($object) {
    $viewer = $this->getViewer();

    if (!PhorgeSpacesNamespaceQuery::getViewerSpacesExist($viewer)) {
      return false;
    }

    return ($object instanceof PhorgeSpacesInterface);
  }

  public function newExportFields() {
    return array(
      id(new PhorgePHIDExportField())
        ->setKey('spacePHID')
        ->setLabel(pht('Space PHID')),
      id(new PhorgeStringExportField())
        ->setKey('space')
        ->setLabel(pht('Space')),
    );
  }

  public function newExportData(array $objects) {
    $viewer = $this->getViewer();

    $space_phids = array();
    foreach ($objects as $object) {
      $space_phids[] = PhorgeSpacesNamespaceQuery::getObjectSpacePHID(
        $object);
    }
    $handles = $viewer->loadHandles($space_phids);

    $map = array();
    foreach ($objects as $object) {
      $space_phid = PhorgeSpacesNamespaceQuery::getObjectSpacePHID(
        $object);

      $map[] = array(
        'spacePHID' => $space_phid,
        'space' => $handles[$space_phid]->getName(),
      );
    }

    return $map;
  }

}
