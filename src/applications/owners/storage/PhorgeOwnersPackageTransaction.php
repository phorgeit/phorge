<?php

final class PhorgeOwnersPackageTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'owners';
  }

  public function getApplicationTransactionType() {
    return PhorgeOwnersPackagePHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeOwnersPackageTransactionType';
  }

}
