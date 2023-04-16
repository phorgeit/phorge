<?php

final class PhameBlogFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $blog = $object;

    $document->setDocumentTitle($blog->getName());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $blog->getDescription());

    $document->addRelationship(
      $blog->isArchived()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $blog->getPHID(),
      PhorgePhameBlogPHIDType::TYPECONST,
      PhorgeTime::getNow());

  }

}
