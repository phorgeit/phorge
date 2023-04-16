<?php

final class PhorgeRefreshCSRFController extends PhorgeAuthController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    return id(new AphrontAjaxResponse())
      ->setContent(
        array(
          'token' => $viewer->getCSRFToken(),
        ));
  }

}
