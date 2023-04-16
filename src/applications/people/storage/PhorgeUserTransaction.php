<?php

final class PhorgeUserTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'user';
  }

  public function getApplicationTransactionType() {
    return PhorgePeopleUserPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeUserTransactionType';
  }

}
