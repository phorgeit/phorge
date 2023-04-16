<?php

final class OwnersEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'owners.edit';
  }

  public function newEditEngine() {
    return new PhorgeOwnersPackageEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new Owners package or edit an existing '.
      'one.');
  }

}
