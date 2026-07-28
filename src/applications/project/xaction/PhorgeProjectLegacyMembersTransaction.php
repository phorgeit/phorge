<?php

final class PhorgeProjectLegacyMembersTransaction
  extends PhabricatorProjectTransactionType {

  // This is a very old transaction type - I think it was removed in 8544d0d00f.
  const TRANSACTIONTYPE = 'project:members';

  public function getIcon() {
    return 'fa-user';
  }

  public function getTitle() {
    $old = $this->getOldValue();
    $new = $this->getNewValue();

    $add = array_diff($new, $old);
    $rem = array_diff($old, $new);

    if ($add && $rem) {
      return pht(
        '%s changed project member(s), added %d: %s; removed %d: %s.',
        $this->renderAuthor(),
        count($add),
        $this->renderHandleList($add),
        count($rem),
        $this->renderHandleList($rem));
    } else if ($add) {
      if (count($add) == 1 && (head($add) == $this->getAuthorPHID())) {
        return pht(
          '%s joined this project.',
          $this->renderAuthor());
      } else {
        return pht(
          '%s added %d project member(s): %s.',
          $this->renderAuthor(),
          count($add),
          $this->renderHandleList($add));
      }
    } else if ($rem) {
      if (count($rem) == 1 && (head($rem) == $this->getAuthorPHID())) {
        return pht(
          '%s left this project.',
          $this->renderAuthor());
      } else {
        return pht(
          '%s removed %d project member(s): %s.',
          $this->renderAuthor(),
          count($rem),
          $this->renderHandleList($rem));
      }
    }
  }

}
