<?php

final class PhorgeMarkupPreviewController
  extends PhorgeController {

  public function processRequest() {
    $request = $this->getRequest();
    $viewer = $request->getUser();

    $text = $request->getStr('text');

    $output = PhorgeMarkupEngine::renderOneObject(
      id(new PhorgeMarkupOneOff())
        ->setPreserveLinebreaks(true)
        ->setDisableCache(true)
        ->setContent($text),
      'default',
      $viewer);

    return id(new AphrontAjaxResponse())
      ->setContent($output);
  }
}
