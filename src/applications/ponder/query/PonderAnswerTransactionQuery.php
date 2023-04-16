<?php

final class PonderAnswerTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PonderAnswerTransaction();
  }

}
