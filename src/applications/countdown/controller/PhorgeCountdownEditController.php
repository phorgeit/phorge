<?php

final class PhorgeCountdownEditController
  extends PhorgeCountdownController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeCountdownEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
