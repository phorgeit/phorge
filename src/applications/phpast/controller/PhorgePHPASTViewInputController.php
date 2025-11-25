<?php

final class PhorgePHPASTViewInputController
  extends PhorgePHPASTViewPanelController {

  public function handleRequest(AphrontRequest $request) {
    $input = $this->getStorageTree()->getInput();
    return $this->buildPHPASTViewPanelResponse($input);
  }
}
