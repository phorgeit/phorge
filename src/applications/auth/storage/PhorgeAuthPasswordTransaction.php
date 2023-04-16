<?php

final class PhorgeAuthPasswordTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'auth';
  }

  public function getApplicationTransactionType() {
    return PhorgeAuthPasswordPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeAuthPasswordTransactionType';
  }
}
