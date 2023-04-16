<?php

final class PhorgePackagesPublisherEditController
  extends PhorgePackagesPublisherController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgePackagesPublisherEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
