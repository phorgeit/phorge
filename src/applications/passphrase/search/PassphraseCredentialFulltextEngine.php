<?php

final class PassphraseCredentialFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $credential = $object;

    $document->setDocumentTitle($credential->getName());

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $credential->getDescription());

    $document->addRelationship(
      $credential->getIsDestroyed()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $credential->getPHID(),
      PassphraseCredentialPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
