<?php

final class NuanceQueueTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new NuanceQueueTransaction();
  }

}
