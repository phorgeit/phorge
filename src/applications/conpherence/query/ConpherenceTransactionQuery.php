<?php

final class ConpherenceTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new ConpherenceTransaction();
  }

}
