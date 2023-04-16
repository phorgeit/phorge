<?php

final class FundBackerTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'fund';
  }

  public function getApplicationTransactionType() {
    return FundBackerPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'FundBackerTransactionType';
  }

}
