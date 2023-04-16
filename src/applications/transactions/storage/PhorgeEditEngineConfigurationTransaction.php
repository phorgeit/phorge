<?php

final class PhorgeEditEngineConfigurationTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'search';
  }

  public function getApplicationTransactionType() {
    return PhorgeEditEngineConfigurationPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeEditEngineTransactionType';
  }

}
