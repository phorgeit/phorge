<?php

final class PhrictionDocumentPHIDType extends PhorgePHIDType {

  const TYPECONST = 'WIKI';

  public function getTypeName() {
    return pht('Phriction Wiki Document');
  }

  public function newObject() {
    return new PhrictionDocument();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePhrictionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhrictionDocumentQuery())
      ->withPHIDs($phids)
      ->needContent(true);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $document = $objects[$phid];
      $content = $document->getContent();

      $title = $content->getTitle();
      $slug = $document->getSlug();
      $status = $document->getStatus();

      $handle->setName($title);
      $handle->setURI(PhrictionDocument::getSlugURI($slug));

      if ($status != PhrictionDocumentStatus::STATUS_EXISTS) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);
      }
    }
  }

}
