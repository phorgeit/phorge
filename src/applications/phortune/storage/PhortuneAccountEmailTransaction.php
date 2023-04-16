<?php

final class PhortuneAccountEmailTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'phortune';
  }

  public function getApplicationTransactionType() {
    return PhortuneAccountEmailPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhortuneAccountEmailTransactionType';
  }

}
