<?php

final class PhorgeFlagDeleteController extends PhorgeFlagController {


  public function handleRequest(AphrontRequest $request) {
    $viewer = $request->getViewer();
    $id = $request->getURIData('id');

    $flag = id(new PhorgeFlag())->load($id);
    if (!$flag) {
      return new Aphront404Response();
    }

    if ($flag->getOwnerPHID() != $viewer->getPHID()) {
      return new Aphront400Response();
    }

    $flag->delete();

    return id(new AphrontReloadResponse())->setURI('/flag/');
  }

}
