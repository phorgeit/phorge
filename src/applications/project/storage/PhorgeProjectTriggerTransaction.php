<?php

final class PhorgeProjectTriggerTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'project';
  }

  public function getApplicationTransactionType() {
    return PhorgeProjectTriggerPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeProjectTriggerTransactionType';
  }

}
