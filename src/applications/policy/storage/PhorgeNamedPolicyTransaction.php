<?php

final class PhorgeNamedPolicyTransaction
  extends PhabricatorModularTransaction {

  public function getApplicationName() {
    return 'policy';
  }

  public function getApplicationTransactionType() {
    return PhorgePolicyPHIDTypeNamedPolicy::TYPECONST;
  }

  public function getApplicationTransactionCommentObject() {
    return new PhorgeNamedPolicyTransactionComment();
  }

  public function getBaseTransactionClass() {
    return PhorgePolicyNamedPolicyTransactionType::class;
  }

}
