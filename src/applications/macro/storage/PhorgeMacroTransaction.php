<?php

final class PhorgeMacroTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'file';
  }

  public function getTableName() {
    return 'macro_transaction';
  }

  public function getApplicationTransactionType() {
    return PhorgeMacroMacroPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgeMacroTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'PhorgeMacroTransactionType';
  }


}
