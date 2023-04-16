<?php

final class LegalpadTransaction extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'legalpad';
  }

  public function getApplicationTransactionType() {
    return PhorgeLegalpadDocumentPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new LegalpadTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'LegalpadDocumentTransactionType';
  }

}
