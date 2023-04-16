<?php

final class PhortuneSubscriptionTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'phortune';
  }

  public function getApplicationTransactionType() {
    return PhortuneSubscriptionPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhortuneSubscriptionTransactionType';
  }

}
