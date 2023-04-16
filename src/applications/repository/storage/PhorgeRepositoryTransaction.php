<?php

final class PhorgeRepositoryTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'repository';
  }

  public function getApplicationTransactionType() {
    return PhorgeRepositoryRepositoryPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeRepositoryTransactionType';
  }

}
