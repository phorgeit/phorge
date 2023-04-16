<?php

final class PhorgeApplicationTransactionWarningException
  extends Exception {

  private $xactions;

  public function __construct(array $xactions) {
    $this->xactions = $xactions;
    parent::__construct();
  }

  public function getTransactions() {
    return $this->xactions;
  }

}
