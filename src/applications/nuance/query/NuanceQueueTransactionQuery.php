<?php

final class NuanceQueueTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new NuanceQueueTransaction();
  }

}
