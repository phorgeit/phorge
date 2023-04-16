<?php

final class PhorgeProjectTriggerListController
  extends PhorgeProjectTriggerController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeProjectTriggerSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

}
