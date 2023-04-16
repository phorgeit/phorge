<?php

final class DifferentialDiffTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new DifferentialDiffTransaction();
  }

}
