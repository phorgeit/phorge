<?php

final class DifferentialRevisionFulltextEngine
  extends PhorgeFulltextEngine {

  protected function buildAbstractDocument(
    PhorgeSearchAbstractDocument $document,
    $object) {

    $revision = id(new DifferentialRevisionQuery())
      ->setViewer($this->getViewer())
      ->withPHIDs(array($object->getPHID()))
      ->needReviewers(true)
      ->executeOne();

    // TODO: This isn't very clean, but custom fields currently rely on it.
    $object->attachReviewers($revision->getReviewers());

    $document->setDocumentTitle($revision->getTitle());

    $document->addRelationship(
      PhorgeSearchRelationship::RELATIONSHIP_AUTHOR,
      $revision->getAuthorPHID(),
      PhorgePeopleUserPHIDType::TYPECONST,
      $revision->getDateCreated());

    $document->addRelationship(
      $revision->isClosed()
        ? PhorgeSearchRelationship::RELATIONSHIP_CLOSED
        : PhorgeSearchRelationship::RELATIONSHIP_OPEN,
      $revision->getPHID(),
      DifferentialRevisionPHIDType::TYPECONST,
      PhorgeTime::getNow());

    // If a revision needs review, the owners are the reviewers. Otherwise, the
    // owner is the author (e.g., accepted, rejected, closed).
    if ($revision->isNeedsReview()) {
      $reviewers = $revision->getReviewerPHIDs();
      $reviewers = array_fuse($reviewers);

      if ($reviewers) {
        foreach ($reviewers as $phid) {
          $document->addRelationship(
            PhorgeSearchRelationship::RELATIONSHIP_OWNER,
            $phid,
            PhorgePeopleUserPHIDType::TYPECONST,
            $revision->getDateModified()); // Bogus timestamp.
        }
      } else {
        $document->addRelationship(
          PhorgeSearchRelationship::RELATIONSHIP_UNOWNED,
          $revision->getPHID(),
          PhorgePeopleUserPHIDType::TYPECONST,
          $revision->getDateModified()); // Bogus timestamp.
      }
    } else {
      $document->addRelationship(
        PhorgeSearchRelationship::RELATIONSHIP_OWNER,
        $revision->getAuthorPHID(),
        PhorgePHIDConstants::PHID_TYPE_VOID,
        $revision->getDateCreated());
    }
  }
}
