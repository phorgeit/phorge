<?php

final class PhorgeBadgesTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhorgeBadgesTransaction();
  }

}
