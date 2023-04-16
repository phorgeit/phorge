<?php

final class PhamePostTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhamePostTransaction();
  }

}
