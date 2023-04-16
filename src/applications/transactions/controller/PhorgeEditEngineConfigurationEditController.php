<?php

final class PhorgeEditEngineConfigurationEditController
  extends PhorgeEditEngineController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $target_engine_key = $request->getURIData('engineKey');

    $target_engine = PhorgeEditEngine::getByKey(
      $viewer,
      $target_engine_key);
    if (!$target_engine) {
      return new Aphront404Response();
    }

    $this->setEngineKey($target_engine->getEngineKey());

    return id(new PhorgeEditEngineConfigurationEditEngine())
      ->setTargetEngine($target_engine)
      ->setController($this)
      ->buildResponse();
  }

}
