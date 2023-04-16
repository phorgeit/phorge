<?php

abstract class PassphraseCredentialTransactionType
  extends PhorgeModularTransactionType {

  public function destroySecret($secret_id) {
    $table = new PassphraseSecret();
    queryfx(
      $table->establishConnection('w'),
      'DELETE FROM %T WHERE id = %d',
      $table->getTableName(),
      $secret_id);
  }

}
