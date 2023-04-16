<?php

final class PhorgeFileTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'file';
  }

  public function getApplicationTransactionType() {
    return PhorgeFileFilePHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgeFileTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'PhorgeFileTransactionType';
  }

}
