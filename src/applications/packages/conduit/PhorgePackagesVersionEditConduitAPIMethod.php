<?php

final class PhorgePackagesVersionEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'packages.version.edit';
  }

  public function newEditEngine() {
    return new PhorgePackagesVersionEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new version or edit an existing one.');
  }

}
