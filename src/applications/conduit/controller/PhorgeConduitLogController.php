<?php

final class PhorgeConduitLogController
  extends PhorgeConduitController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeConduitLogSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

}
