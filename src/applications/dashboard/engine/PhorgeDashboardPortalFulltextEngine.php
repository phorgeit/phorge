<?php

final class PhorgeDashboardPortalFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $portal = $object;

    $document->setDocumentTitle($portal->getName());

    $document->addRelationship(
      $portal->isArchived()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $portal->getPHID(),
      PhorgeDashboardPortalPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
