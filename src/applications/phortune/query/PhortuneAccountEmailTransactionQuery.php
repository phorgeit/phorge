<?php

final class PhortuneAccountEmailTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhortuneAccountEmailTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPhortuneApplication::class;
  }

}
