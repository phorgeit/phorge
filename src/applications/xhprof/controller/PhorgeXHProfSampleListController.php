<?php

final class PhorgeXHProfSampleListController
  extends PhorgeXHProfController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeXHProfSampleSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

}
