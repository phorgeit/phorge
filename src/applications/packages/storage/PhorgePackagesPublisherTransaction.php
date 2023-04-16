<?php

final class PhorgePackagesPublisherTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'packages';
  }

  public function getApplicationTransactionType() {
    return PhorgePackagesPublisherPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgePackagesPublisherTransactionType';
  }

}
