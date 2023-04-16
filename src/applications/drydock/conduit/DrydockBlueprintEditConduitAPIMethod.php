<?php

final class DrydockBlueprintEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'drydock.blueprint.edit';
  }

  public function newEditEngine() {
    return new DrydockBlueprintEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create or edit a blueprint.');
  }

}
