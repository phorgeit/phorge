<?php

final class PhorgeProfileMenuItemIndexEngineExtension
  extends PhorgeEdgeIndexEngineExtension {

  const EXTENSIONKEY = 'profile.menu.item';

  public function getExtensionName() {
    return pht('Profile Menu Item');
  }

  public function shouldIndexObject($object) {
    if (!($object instanceof PhorgeProfileMenuItemConfiguration)) {
      return false;
    }

    return true;
  }

  protected function getIndexEdgeType() {
    return PhorgeProfileMenuItemAffectsObjectEdgeType::EDGECONST;
  }

  protected function getIndexDestinationPHIDs($object) {
    return $object->getAffectedObjectPHIDs();
  }

}
