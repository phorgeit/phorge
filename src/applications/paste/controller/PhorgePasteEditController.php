<?php

final class PhorgePasteEditController extends PhorgePasteController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgePasteEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
