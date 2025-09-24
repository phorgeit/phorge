<?php

final class ConpherenceRoomEditController
  extends ConpherenceController {

  public function handleRequest(AphrontRequest $request) {
    $id = $request->getURIData('id');
    if (!$id) {
      $this->requireApplicationCapability(
        ConpherenceCreateRoomCapability::CAPABILITY);
    }

    return id(new ConpherenceEditEngine())
      ->setController($this)
      ->buildResponse();
  }
}
