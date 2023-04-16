<?php

final class PhorgePackagesPublisherEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'packages.publisher.edit';
  }

  public function newEditEngine() {
    return new PhorgePackagesPublisherEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new publisher or edit an existing one.');
  }

}
