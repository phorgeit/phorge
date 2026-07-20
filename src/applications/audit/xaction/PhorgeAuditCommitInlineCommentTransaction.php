<?php

final class PhorgeAuditCommitInlineCommentTransaction
  extends PhorgeAuditCommitTransactionType {

  const TRANSACTIONTYPE = 'audit:inline';

  public function generateOldValue($object) {
    return null;
  }

  public function getTitle() {
    return pht(
      '%s added inline comments.',
      $this->renderAuthor());
  }

  public function getTitleForFeed() {
    return pht(
      '%s added inline comments to %s.',
      $this->renderAuthor(),
      $this->renderObject());
  }

  public function getTransactionHasEffect($object, $old, $new) {
    return true;
  }

}
