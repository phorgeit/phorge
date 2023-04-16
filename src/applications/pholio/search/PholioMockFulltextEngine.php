<?php

final class PholioMockFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $mock = $object;

    $document->setDocumentTitle($mock->getName());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $mock->getDescription());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $mock->getAuthorPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $mock->getDateCreated());

    $document->addRelationship(
      $mock->isClosed()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $mock->getPHID(),
      PholioMockPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
