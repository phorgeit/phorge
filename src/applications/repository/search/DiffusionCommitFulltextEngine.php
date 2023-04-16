<?php

final class DiffusionCommitFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $commit = id(new DiffusionCommitQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs(array($object->getPHID()))
      ->needCommitData(true)
      ->executeOne();

    $repository = $commit->getRepository();
    $commit_data = $commit->getCommitData();

    $date_created = $commit->getEpoch();
    $commit_message = $commit_data->getCommitMessage();
    $author_phid = $commit_data->getCommitDetail('authorPHID');

    $monogram = $commit->getMonogram();
    $summary = $commit_data->getSummary();

    $title = "{$monogram} {$summary}";

    $document
      ->setDocumentCreated($date_created)
      ->setDocumentModified($date_created)
      ->setDocumentTitle($title);

    $document->addField(
      PhorgeSearchDocumentFieldType::FIELD_BODY,
      $commit_message);

    if ($author_phid) {
      $document->addRelationship(
        PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
        $author_phid,
        PhorgePeopleUserPHIDType::TYPECONST,
        $date_created);
    }

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_REPOSITORY,
      $repository->getPHID(),
      PhorgeRepositoryRepositoryPHIDType::TYPECONST,
      $date_created);

    $document->addRelationship(
      $commit->isUnreachable()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $commit->getPHID(),
      PhorgeRepositoryCommitPHIDType::TYPECONST,
      PhorgeTime::getNow());
  }
}
