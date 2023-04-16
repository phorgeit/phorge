<?php

final class PhorgeFileTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhorgeFileTransaction();
  }

}
