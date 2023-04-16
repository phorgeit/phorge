<?php

final class NuanceSourceTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new NuanceSourceTransaction();
  }

}
