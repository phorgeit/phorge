<?php

final class DifferentialDiffTransaction
  extends PhabricatorModularTransaction {

  const TYPE_DIFF_CREATE = 'differential:diff:create';

  public function getBaseTransactionClass() {
    return DifferentialDiffTransactionType::class;
  }

  public function getApplicationName() {
    return 'differential';
  }

  public function getApplicationTransactionType() {
    return DifferentialDiffPHIDType::TYPECONST;
  }

}
