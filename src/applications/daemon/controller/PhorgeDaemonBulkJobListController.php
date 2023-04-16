<?php

final class PhorgeDaemonBulkJobListController
  extends PhorgeDaemonBulkJobController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeWorkerBulkJobSearchEngine())
      ->setController($this)
      ->buildResponse();
  }

}
