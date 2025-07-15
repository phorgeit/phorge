<?php

final class DifferentialRevisionRequiredReviewerActionResultBucket
  extends DifferentialRevisionResultBucket {

  const BUCKETKEY = 'review-action';

  const KEY_REJECTED = 'rejected-review';
  const KEY_BLOCKING = 'blocking-review';
  const KEY_SHOULDREVIEW = 'should-review';

  private $objects;

  public function getResultBucketName() {
    return pht('Bucket by Reviewer Required Action');
  }

  protected function buildResultGroups(
    PhabricatorSavedQuery $query,
    array $objects) {

    $this->objects = $objects;

    $phids = $query->getEvaluatedParameter('reviewerPHIDs');
    if (!$phids) {
      throw new Exception(
        pht(
          'You can not bucket results by required reviewer action without '.
          'specifying "Reviewer".'));
    }
    $phids = array_fuse($phids);

    // We are only interested in revisions for review
    $this->filterRevisionsAuthored($phids);

    // Before continuing, throw away any revisions which have been
    // explicitly resigned from.

    // The goal is to allow users to resign from revisions they don't want to
    // review to get these revisions off their dashboard, even if there are
    // other project or package reviewers which they have authority over.
    $this->filterResigned($phids);

    // We also throw away revisions not ready for review.
    // This includes drafts, accepted, waiting on other reviewers or the author
    $this->filterDrafts($phids);
    $this->filterRevisionsAccepted($phids);
    $this->filterWaitingOnAuthors($phids);

    $groups = array();

    $rejected = $this->filterRejected($phids);
    if ($rejected) {
      $groups[] = $this->newGroup()
        ->setName(pht('Rejected'))
        ->setKey(self::KEY_REJECTED)
        ->setObjects($rejected);
    }

    $blocking = $this->filterBlocking($phids);
    if ($blocking) {
      $groups[] = $this->newGroup()
        ->setName(pht('Blocking'))
        ->setKey(self::KEY_BLOCKING)
        ->setObjects($blocking);
    }

    $shouldReview = $this->filterShouldReview($phids);
    if ($shouldReview) {
      $groups[] = $this->newGroup()
        ->setName(pht('Ready to Review'))
        ->setKey(self::KEY_SHOULDREVIEW)
        ->setObjects($shouldReview);
    }

    return $groups;
  }

  // It would be better if this filter only included revisions which had been updated
  // since they were last rejected by the reviewer, but that seems difficult without a larger
  // refactor.
  private function filterRejected(array $phids) {
    $blocking = array(
      DifferentialReviewerStatus::STATUS_REJECTED,
      DifferentialReviewerStatus::STATUS_REJECTED_OLDER,
    );
    $blocking = array_fuse($blocking);

    $objects = $this->getRevisionsUnderReview($this->objects, $phids);

    $results = array();
    foreach ($objects as $key => $object) {
      if (!$this->hasReviewersWithStatus($object, $phids, $blocking)) {
        continue;
      }

      $results[$key] = $object;
      unset($this->objects[$key]);
    }

    return $results;
  }

  private function filterBlocking(array $phids) {
    $blocking = array(
      DifferentialReviewerStatus::STATUS_BLOCKING,
    );
    $blocking = array_fuse($blocking);

    $objects = $this->getRevisionsUnderReview($this->objects, $phids);

    $results = array();
    foreach ($objects as $key => $object) {
      if (!$this->hasReviewersWithStatus($object, $phids, $blocking)) {
        continue;
      }

      $results[$key] = $object;
      unset($this->objects[$key]);
    }

    return $results;
  }

  private function filterShouldReview(array $phids) {
    $reviewing = array(
      DifferentialReviewerStatus::STATUS_ADDED,
      DifferentialReviewerStatus::STATUS_COMMENTED,
    );
    $reviewing = array_fuse($reviewing);

    $objects = $this->getRevisionsUnderReview($this->objects, $phids);

    $results = array();
    foreach ($objects as $key => $object) {
      if (!$this->hasReviewersWithStatus($object, $phids, $reviewing, true)) {
        continue;
      }

      $results[$key] = $object;
      unset($this->objects[$key]);
    }

    return $results;
  }

  private function filterWaitingOnAuthors(array $phids) {
    $statuses = array(
      DifferentialRevisionStatus::ACCEPTED,
      DifferentialRevisionStatus::CHANGES_PLANNED,
    );
    $statuses = array_fuse($statuses);

    $objects = $this->getRevisionsNotAuthored($this->objects, $phids);

    $results = array();
    foreach ($objects as $key => $object) {
      if (empty($statuses[$object->getModernRevisionStatus()])) {
        continue;
      }

      $results[$key] = $object;
      unset($this->objects[$key]);
    }

    return $results;
  }

  private function filterResigned(array $phids) {
    $resigned = array(
      DifferentialReviewerStatus::STATUS_RESIGNED,
    );
    $resigned = array_fuse($resigned);

    $objects = $this->getRevisionsNotAuthored($this->objects, $phids);

    $results = array();
    foreach ($objects as $key => $object) {
      if (!$this->hasReviewersWithStatus($object, $phids, $resigned)) {
        continue;
      }

      $results[$key] = $object;
      unset($this->objects[$key]);
    }

    return $results;
  }

  private function filterRevisionsAccepted(array $phids) {
    $accepted = array(
      DifferentialReviewerStatus::STATUS_ACCEPTED,
    );
    $accepted = array_fuse($accepted);

    $objects = $this->getRevisionsUnderReview($this->objects, $phids);

    $results = array();
    foreach ($objects as $key => $object) {
      if (!$this->hasReviewersWithStatus($object, $phids, $accepted, true)) {
        continue;
      }

      $results[$key] = $object;
      unset($this->objects[$key]);
    }

    return $results;
  }

  private function filterRevisionsAuthored(array $phids) {
    $objects = $this->getRevisionsAuthored($this->objects, $phids);

    $results = array();
    foreach ($objects as $key => $object) {

      $results[$key] = $object;
      unset($this->objects[$key]);
    }

    return $results;
  }

  private function filterDrafts(array $phids) {
    $results = array();
    foreach ($this->objects as $key => $object) {
      if (!$object->isDraft()) {
        continue;
      }

      $results[$key] = $object;
      unset($this->objects[$key]);
    }

    return $results;
  }

}
