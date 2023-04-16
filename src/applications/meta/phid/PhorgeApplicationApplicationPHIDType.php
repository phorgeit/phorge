<?php

final class PhorgeApplicationApplicationPHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'APPS';

  public function getTypeName() {
    return pht('Application');
  }

  public function getTypeIcon() {
    return 'fa-globe';
  }

  public function newObject() {
    return null;
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeApplicationsApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeApplicationQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $application = $objects[$phid];

      $handle
        ->setName($application->getName())
        ->setURI($application->getApplicationURI())
        ->setIcon($application->getIcon());
    }
  }

}
