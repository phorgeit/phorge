<?php

final class PhorgePolicyEditNamedPolicyController
  extends PhabricatorNamedPolicyController {

  public function handleRequest(AphrontRequest $request) {
    return id(new PhorgeNamedPolicyEditEngine())
      ->setController($this)
      ->buildResponse();
  }

}
