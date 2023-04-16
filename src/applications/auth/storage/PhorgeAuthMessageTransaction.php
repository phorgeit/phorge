<?php

final class PhorgeAuthMessageTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'auth';
  }

  public function getApplicationTransactionType() {
    return PhorgeAuthMessagePHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeAuthMessageTransactionType';
  }

}
