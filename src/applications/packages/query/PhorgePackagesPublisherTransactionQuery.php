<?php

final class PhorgePackagesPublisherTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgePackagesPublisherTransaction();
  }

}
