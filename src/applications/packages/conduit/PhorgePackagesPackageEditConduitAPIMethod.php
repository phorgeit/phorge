<?php

final class PhorgePackagesPackageEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'packages.package.edit';
  }

  public function newEditEngine() {
    return new PhorgePackagesPackageEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new package or edit an existing one.');
  }

}
