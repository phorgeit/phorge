<?php

final class PhorgePackagesPackageTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'packages';
  }

  public function getApplicationTransactionType() {
    return PhorgePackagesPackagePHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgePackagesPackageTransactionType';
  }

}
