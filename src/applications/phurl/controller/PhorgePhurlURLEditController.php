<?php

final class PhorgePhurlURLEditController
  extends PhorgePhurlController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgePhurlURLEditEngine())
      ->setController($this)
      ->buildResponse();
  }
}
