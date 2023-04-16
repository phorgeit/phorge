<?php

final class PhorgePhurlURLTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhorgePhurlURLTransaction();
  }

}
