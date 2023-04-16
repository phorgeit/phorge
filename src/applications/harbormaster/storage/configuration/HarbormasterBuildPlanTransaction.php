<?php

final class HarbormasterBuildPlanTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'harbormaster';
  }

  public function getApplicationTransactionType() {
    return HarbormasterBuildPlanPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'HarbormasterBuildPlanTransactionType';
  }

}
