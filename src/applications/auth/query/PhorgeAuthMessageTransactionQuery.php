<?php

final class PhorgeAuthMessageTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeAuthMessageTransaction();
  }

}
