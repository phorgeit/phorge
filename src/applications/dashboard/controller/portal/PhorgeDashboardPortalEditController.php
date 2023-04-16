<?php

final class PhorgeDashboardPortalEditController
  extends PhorgeDashboardPortalController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeDashboardPortalEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
