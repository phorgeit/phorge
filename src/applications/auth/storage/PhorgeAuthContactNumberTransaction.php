<?php

final class PhorgeAuthContactNumberTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'auth';
  }

  public function getApplicationTransactionType() {
    return PhorgeAuthContactNumberPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeAuthContactNumberTransactionType';
  }

}
