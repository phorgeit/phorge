<?php

final class PhorgeBadgesEditController extends
  PhorgeBadgesController {
  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeBadgesEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
