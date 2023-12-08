<?php

final class PhabricatorRepositoryFulltextEngine
  extends PhabricatorFulltextEngine {

  protected function buildAbstractDocument(
    PhabricatorSearchAbstractDocument $document,
    $object) {
    $repo = $object;

    $title_fields = array(
      $repo->getName(),
      $repo->getRepositorySlug(),
    );
    $callsign = $repo->getCallsign();
    if ($callsign) {
      $title_fields[] = $callsign;
      $title_fields[] = 'r'.$callsign;
    }

    $document->setDocumentTitle(implode("\n", $title_fields));
    $document->addField(
      PhabricatorSearchDocumentFieldType::FIELD_BODY,
      $repo->getDetail('description'));

    $document->setDocumentCreated($repo->getDateCreated());
    $document->setDocumentModified($repo->getDateModified());

    $document->addRelationship(
      $repo->isTracked()
        ? PhabricatorSearchRelationship::RELATIONSHIP_OPEN
        : PhabricatorSearchRelationship::RELATIONSHIP_CLOSED,
      $repo->getPHID(),
      PhabricatorRepositoryRepositoryPHIDType::TYPECONST,
      PhabricatorTime::getNow());
  }

}
