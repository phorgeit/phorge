<?php

final class PhrictionTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhrictionTransaction();
  }

}
