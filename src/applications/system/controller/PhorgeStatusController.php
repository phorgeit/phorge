<?php

final class PhorgeStatusController extends PhorgeController {

  public function shouldRequireLogin() {
    return false;
  }

  public function processRequest() {
    $response = new AphrontWebpageResponse();
    $response->setContent("ALIVE\n");
    return $response;
  }
}
