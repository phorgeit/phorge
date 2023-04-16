<?php

abstract class PhorgePackagesEditEngine
  extends PhorgeEditEngine {

  public function isEngineConfigurable() {
    return false;
  }

  public function getEngineApplicationClass() {
    return 'PhorgePackagesApplication';
  }

}
