<?php

final class PhorgeAuthContactNumberEditController
  extends PhorgeAuthContactNumberController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeAuthContactNumberEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
