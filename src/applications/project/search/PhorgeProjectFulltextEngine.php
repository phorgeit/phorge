<?php

final class PhorgeProjectFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $project = $object;
    $viewer = $this->getViewer();

    // Reload the project to get slugs.
    $project = id(new PhorgeProjectQuery())
      ->withIDs(array($project->getID()))
      ->setViewer($viewer)
      ->needSlugs(true)
      ->executeOne();

    $project->updateDatasourceTokens();

    $slugs = array();
    foreach ($project->getSlugs() as $slug) {
      $slugs[] = $slug->getSlug();
    }
    $body = implode("\n", $slugs);

    $document
      ->setDocumentTitle($project->getDisplayName())
      ->addField(PhorgeSearchDocumentFieldType::FIELD_BODY, $body);

    $document->addRelationship(
      $project->isArchived()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $project->getPHID(),
      PhorgeProjectProjectPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }

}
