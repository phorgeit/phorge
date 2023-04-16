<?php

final class ManiphestTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new ManiphestTransaction();
  }

}
