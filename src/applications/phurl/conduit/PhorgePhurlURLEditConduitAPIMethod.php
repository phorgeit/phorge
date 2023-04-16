<?php

final class PhorgePhurlURLEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'phurls.edit';
  }

  public function newEditEngine() {
    return new PhorgePhurlURLEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new Phurl URL or edit an existing one.');
  }
}
