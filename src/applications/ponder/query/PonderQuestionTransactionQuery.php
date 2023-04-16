<?php

final class PonderQuestionTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PonderQuestionTransaction();
  }

}
