<?php

final class AlmanacDeviceEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'almanac.device.edit';
  }

  public function newEditEngine() {
    return new AlmanacDeviceEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new device or edit an existing one.');
  }

}
