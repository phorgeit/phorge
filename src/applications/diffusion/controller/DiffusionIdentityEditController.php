<?php

final class DiffusionIdentityEditController
  extends DiffusionController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeRepositoryIdentityEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
