<?php

final class PhorgeAuthSSHKeyListController
  extends PhorgeAuthSSHKeyController {

  public function shouldAllowPublic() {
    return true;
  }

  public function handleRequest(AphrontRequest $request) {
    $object_phid = $request->getURIData('forPHID');
    $object = $this->loadSSHKeyObject($object_phid, false);
    if (!$object) {
      return new Aphront404Response();
    }

    $engine = id(new PhorgeAuthSSHKeySearchEngine())
      ->setSSHKeyObject($object);

    return id($engine)
      ->setController($this)
      ->buildResponse();
  }

}
