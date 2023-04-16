<?php

final class HarbormasterBuildableTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'harbormaster';
  }

  public function getApplicationTransactionType() {
    return HarbormasterBuildablePHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'HarbormasterBuildableTransactionType';
  }

}
