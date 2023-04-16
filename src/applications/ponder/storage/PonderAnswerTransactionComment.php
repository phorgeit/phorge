<?php

final class PonderAnswerTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PonderAnswerTransaction();
  }

}
