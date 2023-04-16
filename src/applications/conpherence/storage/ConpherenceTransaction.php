<?php

final class ConpherenceTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'conpherence';
  }

  public function getApplicationTransactionType() {
    return PhorgeConpherenceThreadPHIDType::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new ConpherenceTransactionComment();
  }

  public function getBaseTransactionClass() {
    return 'ConpherenceThreadTransactionType';
  }

}
