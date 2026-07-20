<?php

final class PhorgeAuditCommitCommitTransaction
  extends PhorgeAuditCommitTransactionType {

  const TRANSACTIONTYPE = 'audit:commit';

  public function getActionName() {
    return pht('Committed');
  }

  public function generateOldValue($object) {
    return null;
  }

  public function newRemarkupChanges() {
    $value = $this->getNewValue();
    return array(
      $this->newRemarkupChange()
        ->setNewValue($value['description']),
    );
  }

  public function getTitle() {

    $new = $this->getNewValue();
    $author = null;
    if ($new['authorPHID']) {
      $author = $this->renderHandle($new['authorPHID']);
    } else {
      $author = $new['authorName'];
    }

    $committer = null;
    if ($new['committerPHID']) {
      $committer = $this->renderHandle($new['committerPHID']);
    } else if ($new['committerName']) {
      $committer = $new['committerName'];
    }

    $commit = $this->renderObject();

    if (!$committer) {
      $committer = $author;
      $author = null;
    }

    if ($author) {
      $title = pht(
        '%s committed %s (authored by %s).',
        $committer,
        $commit,
        $author);
    } else {
      $title = pht(
        '%s committed %s.',
        $committer,
        $commit);
    }
    return $title;
  }

  public function getTitleForFeed() {

    $new = $this->getNewValue();
    $author = null;

    if ($new['authorPHID']) {
      $author = $this->renderHandle($new['authorPHID']);
    } else {
      $author = $new['authorName'];
    }

    $committer = null;
    if ($new['committerPHID']) {
      $committer = $this->renderHandle($new['committerPHID']);
    } else if ($new['committerName']) {
      $committer = $new['committerName'];
    }

    if (!$committer) {
      $committer = $author;
      $author = null;
    }

    // Show both Author and Committer only if they are different.
    $show_both = $author && $committer;
    if ($show_both) {
      if ($new['authorPHID']) {
        $show_both = $new['authorPHID'] !== $new['committerPHID'];
      } else if (phutil_nonempty_string($new['authorName'])) {
        $show_both = $new['authorName'] !== $new['committerName'];
      }
    }

    if ($show_both) {
      $title = pht(
        '%s committed %s (authored by %s).',
        $committer,
        $this->renderObject(),
        $author);
    } else {
      $title = pht(
        '%s committed %s.',
        $committer,
        $this->renderObject());
    }
    return $title;
  }

  public function getActionStrength() {
    return 300;
  }

}
