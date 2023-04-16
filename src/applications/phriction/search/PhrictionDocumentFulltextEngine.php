<?php

final class PhrictionDocumentFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $wiki = id(new PhrictionDocumentQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs(array($document->getPHID()))
      ->needContent(true)
      ->executeOne();

    $content = $wiki->getContent();

    $document->setDocumentTitle($content->getTitle());

    // TODO: These are not quite correct, but we don't currently store the
    // proper dates in a way that's easy to get to.
    $document
      ->setDocumentCreated($content->getDateCreated())
      ->setDocumentModified($content->getDateModified());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $content->getContent());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $content->getAuthorPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $content->getDateCreated());

    $document->addRelationship(
      ($wiki->getStatus() == PhrictionDocumentStatus::STATUS_EXISTS)
        ? PhorgeSearchRelationship::RELATIONSHIP_OPEN
        : PhorgeSearchRelationship::RELATIONSHIP_CLOSED,
      $wiki->getPHID(),
      PhrictionDocumentPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }
}
