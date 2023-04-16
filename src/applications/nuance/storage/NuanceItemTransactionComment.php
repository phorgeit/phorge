<?php

final class NuanceItemTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new NuanceItemTransaction();
  }

}
