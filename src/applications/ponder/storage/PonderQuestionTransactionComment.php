<?php

final class PonderQuestionTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PonderQuestionTransaction();
  }

}
