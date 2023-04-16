<?php

final class PhorgeDashboardFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $dashboard = $object;

    $document->setDocumentTitle($dashboard->getName());

    $document->addRelationship(
      $dashboard->isArchived()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $dashboard->getPHID(),
      PhorgeDashboardDashboardPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
