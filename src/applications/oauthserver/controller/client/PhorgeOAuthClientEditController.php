<?php

final class PhorgeOAuthClientEditController
  extends PhorgeOAuthClientController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeOAuthServerEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
