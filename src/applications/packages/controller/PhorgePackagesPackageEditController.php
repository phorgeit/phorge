<?php

final class PhorgePackagesPackageEditController
  extends PhorgePackagesPackageController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgePackagesPackageEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
