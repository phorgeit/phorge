<?php

final class PhorgeRepositoryFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {
    $repo = $object;
    $document->setDocumentTitle($repo->getName());
    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $repo->getRepositorySlug()."\n".$repo->getDetail('description'));

    $document->setDocumentCreated($repo->getDateCreated());
    $document->setDocumentModified($repo->getDateModified());

    $document->addRelationship(
      $repo->isTracked()
        ? PhorgeSearchRelationship::RELATIONSHIP_OPEN
        : PhorgeSearchRelationship::RELATIONSHIP_CLOSED,
      $repo->getPHID(),
      PhorgeRepositoryRepositoryPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
