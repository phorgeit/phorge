<?php

final class HeraldTranscriptPHIDType extends PhorgePHIDType {

  const TYPECONST = 'HLXS';

  public function getTypeName() {
    return pht('Herald Transcript');
  }

  public function newObject() {
    return new HeraldTranscript();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeHeraldApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new HeraldTranscriptQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $xscript = $objects[$phid];

      $id = $xscript->getID();

      $handle->setName(pht('Transcript %s', $id));
      $handle->setURI("/herald/transcript/$id/");
    }
  }

}
