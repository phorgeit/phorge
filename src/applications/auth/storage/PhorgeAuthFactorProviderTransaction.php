<?php

final class PhorgeAuthFactorProviderTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'auth';
  }

  public function getApplicationTransactionType() {
    return PhorgeAuthAuthFactorProviderPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeAuthFactorProviderTransactionType';
  }

}
