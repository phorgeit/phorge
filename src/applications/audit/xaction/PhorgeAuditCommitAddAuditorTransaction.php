<?php

/**
 * @deprecated this transaction can no longer be created after b5722a9963.
 * Only kept for rendering transactions that were created before that.
 */
final class PhorgeAuditCommitAddAuditorTransaction
  extends PhorgeAuditCommitTransactionType {

  const TRANSACTIONTYPE = 'add_auditors';

  public function getActionName() {
    return pht('Added Auditors');
  }

  public function getTitle() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    $author_handle = $this->renderAuthor();
    if (!is_array($old)) {
      $old = array();
    }
    if (!is_array($new)) {
      $new = array();
    }
    $add = array_keys(array_diff_key($new, $old));
    $rem = array_keys(array_diff_key($old, $new));

    if ($add && $rem) {
      return pht(
        '%s edited auditors; added: %s, removed: %s.',
        $author_handle,
        $this->renderHandleList($add),
        $this->renderHandleList($rem));
    } else if ($add) {
      return pht(
        '%s added auditors: %s.',
        $author_handle,
        $this->renderHandleList($add));
    } else if ($rem) {
      return pht(
        '%s removed auditors: %s.',
        $author_handle,
        $this->renderHandleList($rem));
    } else {
      return pht(
        '%s added auditors...',
        $author_handle);
    }
  }

  public function getTitleForFeed() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    $author_handle = $this->renderAuthor();
    $object_handle = $this->renderObject();

    if (!is_array($old)) {
      $old = array();
    }
    if (!is_array($new)) {
      $new = array();
    }
    $add = array_keys(array_diff_key($new, $old));
    $rem = array_keys(array_diff_key($old, $new));

    if ($add && $rem) {
      return pht(
        '%s edited auditors for %s; added: %s, removed: %s.',
        $author_handle,
        $object_handle,
        $this->renderHandleList($add),
        $this->renderHandleList($rem));
    } else if ($add) {
      return pht(
        '%s added auditors to %s: %s.',
        $author_handle,
        $object_handle,
        $this->renderHandleList($add));
    } else if ($rem) {
      return pht(
        '%s removed auditors from %s: %s.',
        $author_handle,
        $object_handle,
        $this->renderHandleList($rem));
    } else {
      return pht(
        '%s added auditors to %s...',
        $author_handle,
        $object_handle);
    }
  }

}
