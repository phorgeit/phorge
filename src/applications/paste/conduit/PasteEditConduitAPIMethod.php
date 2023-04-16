<?php

final class PasteEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'paste.edit';
  }

  public function newEditEngine() {
    return new PhorgePasteEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new paste or edit an existing one.');
  }

}
