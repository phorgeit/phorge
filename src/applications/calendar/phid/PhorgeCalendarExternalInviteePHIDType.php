<?php

final class PhorgeCalendarExternalInviteePHIDType
  extends PhorgePHIDType {

  const TYPECONST = 'CXNV';

  public function getTypeName() {
    return pht('External Invitee');
  }

  public function newObject() {
    return new PhorgeCalendarExternalInvitee();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgeCalendarExternalInviteeQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $invitee = $objects[$phid];

      $name = $invitee->getName();
      $handle->setName($name);
    }
  }
}
