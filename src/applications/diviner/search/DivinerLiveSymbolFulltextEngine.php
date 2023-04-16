<?php

final class DivinerLiveSymbolFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $atom = $object;
    $book = $atom->getBook();

    $document
      ->setDocumentTitle($atom->getTitle())
      ->setDocumentCreated($book->getDateCreated())
      ->setDocumentModified($book->getDateModified());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $atom->getSummary());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_BOOK,
      $atom->getBookPHID(),
      DivinerBookPHIDType::TYPECONST,
      PhorgeTime::getNow());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_REPOSITORY,
      $atom->getRepositoryPHID(),
      PhorgeRepositoryRepositoryPHIDType::TYPECONST,
      PhorgeTime::getNow());

    $document->addRelationship(
      $atom->getGraphHash()
        ? PhorgeSearchRelationship::RELATIONSHIP_OPEN
        : PhorgeSearchRelationship::RELATIONSHIP_CLOSED,
      $atom->getBookPHID(),
      DivinerBookPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
