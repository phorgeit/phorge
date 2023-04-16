<?php

final class PhorgeDashboardPanelFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $panel = $object;

    $document->setDocumentTitle($panel->getName());

    $document->addRelationship(
      $panel->getIsArchived()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $panel->getPHID(),
      PhorgeDashboardPanelPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
