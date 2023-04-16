<?php

final class PhorgeAuthMessageEditController
  extends PhorgeAuthMessageController {

  public function handleRequest(AphrontRequest $request) {
    $this->requireApplicationCapability(
      AuthManageProvidersCapability::CAPABILITY);

    $engine = id(new PhorgeAuthMessageEditEngine())
      ->setController($this);

    $id = $request->getURIData('id');
    if (!$id) {
      $message_key = $request->getStr('messageKey');

      $message_types = PhorgeAuthMessageType::getAllMessageTypes();
      $message_type = idx($message_types, $message_key);
      if (!$message_type) {
        return new Aphront404Response();
      }

      $engine
        ->addContextParameter('messageKey', $message_key)
        ->setMessageType($message_type);
    }

    return $engine->buildResponse();
  }

}
