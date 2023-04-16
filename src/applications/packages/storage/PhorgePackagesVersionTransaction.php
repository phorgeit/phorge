<?php

final class PhorgePackagesVersionTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'packages';
  }

  public function getApplicationTransactionType() {
    return PhorgePackagesVersionPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgePackagesVersionTransactionType';
  }

}
