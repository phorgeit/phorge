<?php

final class FundInitiativeFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $initiative = $object;

    $document->setDocumentTitle($initiative->getName());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $initiative->getOwnerPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $initiative->getDateCreated());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_OWNER,
      $initiative->getOwnerPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $initiative->getDateCreated());

    $document->addRelationship(
      $initiative->isClosed()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $initiative->getPHID(),
      FundInitiativePHIDType::TYPECONST,
      PhorgeTime::getNow());
  }
}
