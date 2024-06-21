<?php

final class PhabricatorPasteTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorPasteTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPasteApplication::class;
  }

}
