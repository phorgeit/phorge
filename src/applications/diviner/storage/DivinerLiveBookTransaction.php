<?php

final class DivinerLiveBookTransaction
  extends PhorgeApplicationTransaction {

  public function getApplicationName() {
    return 'diviner';
  }

  public function getApplicationTransactionType() {
    return DivinerBookPHIDType::TYPECONST;
  }

}
