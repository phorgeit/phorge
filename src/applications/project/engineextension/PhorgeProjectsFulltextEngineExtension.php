<?php

final class PhorgeProjectsFulltextEngineExtension
  extends PhorgeFulltextEngineExtension {

  const EXTENSIONKEY = 'projects';

  public function getExtensionName() {
    return pht('Projects');
  }

  public function shouldEnrichFulltextObject($object) {
    return ($object instanceof PhorgeProjectInterface);
  }

  public function enrichFulltextObject(
    $object,
    PhorgeSearchAbstractDocument $document) {

    $project_phids = PhorgeEdgeQuery::loadDestinationPHIDs(
      $object->getPHID(),
      PhorgeProjectObjectHasProjectEdgeType::EDGECONST);

    if (!$project_phids) {
      return;
    }

    foreach ($project_phids as $project_phid) {
      $document->addRelationship(
        PhorgeSearchRelationship::RELATIONSHIP_PROJECT,
        $project_phid,
        PhorgeProjectProjectPHIDType::TYPECONST,
        $document->getDocumentModified()); // Bogus timestamp.
    }
  }

}
