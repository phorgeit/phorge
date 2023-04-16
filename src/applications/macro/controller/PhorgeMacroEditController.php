<?php

final class PhorgeMacroEditController extends PhameBlogController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeMacroEditEngine())
      ->setController($this)
      ->buildResponse();
  }
}
