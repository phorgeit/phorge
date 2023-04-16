<?php

final class PhorgeUserFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $user = $object;

    $document->setDocumentTitle($user->getFullName());

    $document->addRelationship(
      $user->isUserActivated()
        ? PhorgeSearchRelationship::RELATIONSHIP_OPEN
        : PhorgeSearchRelationship::RELATIONSHIP_CLOSED,
      $user->getPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }
}
