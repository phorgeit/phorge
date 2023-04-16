<?php

final class PhamePostFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $post = $object;

    $document->setDocumentTitle($post->getTitle());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $post->getBody());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $post->getBloggerPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $post->getDateCreated());

    $document->addRelationship(
      $post->isArchived()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $post->getPHID(),
      PhorgePhamePostPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
