<?php

final class PhorgeCalendarEventFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $event = $object;

    $document->setDocumentTitle($event->getName());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $event->getDescription());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $event->getHostPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $event->getDateCreated());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_OWNER,
      $event->getHostPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $event->getDateCreated());

    $document->addRelationship(
      $event->getIsCancelled()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $event->getPHID(),
      PhorgeCalendarEventPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
