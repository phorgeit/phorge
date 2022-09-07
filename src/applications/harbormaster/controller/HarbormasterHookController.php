<?php

final class HarbormasterHookController
  extends HarbormasterController {

  public function shouldRequireLogin() {
    return false;
  }

  public function handleRequest(AphrontRequest $request) {
    $name = $request->getURIData('handler');
    $handler = HarbormasterHookHandler::getHandler($name);

    if (!$handler) {
      throw new Exception(pht('No handler found for %s', $name));
    }

    return $handler->handleRequest($request);
  }

}
