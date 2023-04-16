<?php

final class PhorgeBadgesEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'badge.edit';
  }

  public function newEditEngine() {
    return new PhorgeBadgesEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new badge or edit an existing one.');
  }

}
