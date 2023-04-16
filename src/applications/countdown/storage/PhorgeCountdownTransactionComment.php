<?php

final class PhorgeCountdownTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhorgeCountdownTransaction();
  }

}
