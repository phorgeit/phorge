<?php

final class ManiphestTaskFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $task = $object;

    $document->setDocumentTitle($task->getTitle());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $task->getDescription());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $task->getAuthorPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $task->getDateCreated());

    $document->addRelationship(
      $task->isClosed()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $task->getPHID(),
      ManiphestTaskPHIDType::TYPECONST,
      PhorgeTime::getNow());

    $owner = $task->getOwnerPHID();
    if ($owner) {
      $document->addRelationship(
        PhorgeSearchRelationship::RELATIONSHIP_OWNER,
        $owner,
        PhorgePeopleUserPHIDType::TYPECONST,
        time());
    } else {
      $document->addRelationship(
        PhorgeSearchRelationship::RELATIONSHIP_UNOWNED,
        $task->getPHID(),
        PhorgePHIDConstants::PHID_TYPE_VOID,
        $task->getDateCreated());
    }
  }

}
