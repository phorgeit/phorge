<?php

final class PhorgeAuthSSHKeyTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeAuthSSHKeyTransaction();
  }

}
