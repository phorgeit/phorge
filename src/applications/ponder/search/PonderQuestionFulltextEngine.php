<?php

final class PonderQuestionFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $question = $object;

    $document->setDocumentTitle($question->getTitle());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $question->getContent());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $question->getAuthorPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $question->getDateCreated());
  }
}
