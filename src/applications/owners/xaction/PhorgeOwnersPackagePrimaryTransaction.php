<?php

/**
 * @deprecated
 */
final class PhorgeOwnersPackagePrimaryTransaction
  extends PhorgeOwnersPackageTransactionType {

  const TRANSACTIONTYPE = 'owners.primary';

  public function shouldHide() {
    return true;
  }

}
