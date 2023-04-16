<?php

final class PhorgeXHPASTViewInputController
  extends PhorgeXHPASTViewPanelController {

  public function handleRequest(AphrontRequest $request) {
    $input = $this->getStorageTree()->getInput();
    return $this->buildXHPASTViewPanelResponse($input);
  }
}
