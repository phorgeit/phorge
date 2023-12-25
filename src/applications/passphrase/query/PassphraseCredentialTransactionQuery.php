<?php

final class PassphraseCredentialTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PassphraseCredentialTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorPassphraseApplication::class;
  }

}
