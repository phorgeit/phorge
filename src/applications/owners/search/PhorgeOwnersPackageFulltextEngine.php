<?php

final class PhorgeOwnersPackageFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $package = $object;
    $document->setDocumentTitle($package->getName());

    // TODO: These are bogus, but not currently stored on packages.
    $document->setDocumentCreated(PhorgeTime::getNow());
    $document->setDocumentModified(PhorgeTime::getNow());

    $document->addRelationship(
      $package->isArchived()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $package->getPHID(),
      PhorgeOwnersPackagePHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
