<?php

final class PhrictionDocumentPHIDType extends PhabricatorPHIDType {

  const TYPECONST = 'WIKI';

  public function getTypeName() {
    return pht('Phriction Wiki Document');
  }

  public function newObject() {
    return new PhrictionDocument();
  }

  public function getPHIDTypeApplicationClass() {
    return PhabricatorPhrictionApplication::class;
  }

  protected function buildQueryForObjects(
    PhabricatorObjectQuery $query,
    array $phids) {

    return id(new PhrictionDocumentQuery())
      ->withPHIDs($phids)
      ->needContent(true);
  }

  public function loadHandles(
    PhabricatorHandleQuery $query,
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
        $handle->setStatus(PhabricatorObjectHandle::STATUS_CLOSED);
      }
    }
  }

  /**
   * Check whether a named object is of this PHID type
   * @param string $name Object name
   * @return bool True if the named object is of this PHID type
   */
  public function canLoadNamedObject($name) {
    return preg_match('/.*\/$/', $name);
  }

  public function loadNamedObjects(
    PhabricatorObjectQuery $query,
    array $names) {
      $objects = id(new PhrictionDocumentQuery())
        ->setViewer($query->getViewer())
        ->withSlugs($names)
        ->execute();

      $results = array();
      foreach ($objects as $id => $object) {
        foreach ($names as $name) {
          $results[$name] = $object;
        }
      }

      return $results;
    }

}
