<?php

final class PhorgeProjectColumnTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'project';
  }

  public function getApplicationTransactionType() {
    return PhorgeProjectColumnPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeProjectColumnTransactionType';
  }

}
