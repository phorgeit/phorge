<?php

final class PhorgeRepositoryIdentityTransaction
  extends PhorgeModularTransaction {

  public function getApplicationTransactionType() {
    return PhorgeRepositoryIdentityPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeRepositoryIdentityTransactionType';
  }

  public function getApplicationName() {
    return 'repository';
  }

}
