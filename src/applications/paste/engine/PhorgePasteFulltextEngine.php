<?php

final class PhorgePasteFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $paste = id(new PhorgePasteQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs(array($object->getPHID()))
      ->needContent(true)
      ->executeOne();

    $document->setDocumentTitle($paste->getTitle());

    $document->addRelationship(
      $paste->isArchived()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $paste->getPHID(),
      PhorgePastePastePHIDType::TYPECONST,
      PhorgeTime::getNow());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $paste->getContent());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $paste->getAuthorPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $paste->getDateCreated());
  }

}
