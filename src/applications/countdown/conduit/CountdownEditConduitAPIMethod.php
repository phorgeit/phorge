<?php

final class CountdownEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'countdown.edit';
  }

  public function newEditEngine() {
    return new PhorgeCountdownEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new countdown or edit an existing one.');
  }
}
