<?php

final class PhorgeEditEngineListController
  extends PhorgeEditEngineController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeEditEngineSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

}
