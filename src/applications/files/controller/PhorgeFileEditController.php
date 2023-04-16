<?php

final class PhorgeFileEditController
  extends PhorgeFileController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeFileEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
