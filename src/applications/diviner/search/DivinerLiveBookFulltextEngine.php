<?php

final class DivinerLiveBookFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $book = $object;

    $document->setDocumentTitle($book->getTitle());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $book->getPreface());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_REPOSITORY,
      $book->getRepositoryPHID(),
      PhorgeRepositoryRepositoryPHIDType::TYPECONST,
      $book->getDateCreated());
  }


}
