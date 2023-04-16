<?php

final class PhorgeApplicationApplicationTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'application';
  }

  public function getApplicationTransactionType() {
    return PhorgeApplicationApplicationPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeApplicationTransactionType';
  }

}
