<?php

final class MacroEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'macro.edit';
  }

  public function newEditEngine() {
    return new PhorgeMacroEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new macro or edit an existing one.');
  }

}
