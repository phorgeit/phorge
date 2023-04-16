<?php

final class ManiphestTaskUnlockEngine
  extends PhorgeUnlockEngine {

  public function newUnlockOwnerTransactions($object, $user) {
    return array(
      $this->newTransaction($object)
        ->setTransactionType(ManiphestTaskOwnerTransaction::TRANSACTIONTYPE)
        ->setNewValue($user->getPHID()),
    );
  }

}
