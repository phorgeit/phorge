<?php

final class PhorgeDashboardEditController
  extends PhorgeDashboardController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeDashboardEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
