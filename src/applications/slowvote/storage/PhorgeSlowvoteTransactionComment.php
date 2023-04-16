<?php

final class PhorgeSlowvoteTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhorgeSlowvoteTransaction();
  }

}
