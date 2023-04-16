<?php

final class PassphraseCredentialTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PassphraseCredentialTransaction();
  }

}
