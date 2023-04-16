<?php

final class AlmanacInterfaceEditController
  extends AlmanacDeviceController {

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $engine = id(new AlmanacInterfaceEditEngine())
      ->setController($this);

    $id = $request->getURIData('id');
    if (!$id) {
      $device = id(new AlmanacDeviceQuery())
        ->setViewer($viewer)
        ->withIDs(array($request->getInt('deviceID')))
        ->requireCapabilities(
          array(
            PhorgePolicyCapability::CAN_VIEW,
            PhorgePolicyCapability::CAN_EDIT,
          ))
        ->executeOne();
      if (!$device) {
        return new Aphront404Response();
      }

      $engine
        ->addContextParameter('deviceID', $device->getID())
        ->setDevice($device);
    }

    return $engine->buildResponse();
  }

}
