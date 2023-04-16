<?php

abstract class PhorgeSearchBaseController extends PhorgeController {

  protected function loadRelationshipObject() {
    $request = $this->getRequest();
    $viewer = $this->getViewer();

    $phid = $request->getURIData('sourcePHID');

    return id(new PhorgeObjectQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($phid))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
  }

  protected function loadRelationship($object) {
    $request = $this->getRequest();
    $viewer = $this->getViewer();

    $relationship_key = $request->getURIData('relationshipKey');

    $list = PhorgeObjectRelationshipList::newForObject(
      $viewer,
      $object);

    return $list->getRelationship($relationship_key);
  }

}
