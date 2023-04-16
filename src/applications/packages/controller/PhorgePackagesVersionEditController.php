<?php

final class PhorgePackagesVersionEditController
  extends PhorgePackagesVersionController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgePackagesVersionEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
