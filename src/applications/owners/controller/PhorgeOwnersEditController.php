<?php

final class PhorgeOwnersEditController
  extends PhorgeOwnersController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeOwnersPackageEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
