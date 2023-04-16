<?php

final class HarbormasterBuildTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'harbormaster';
  }

  public function getApplicationTransactionType() {
    return HarbormasterBuildPHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'HarbormasterBuildTransactionType';
  }

}
