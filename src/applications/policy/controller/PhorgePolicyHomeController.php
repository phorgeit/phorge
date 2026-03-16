<?php

final class PhorgePolicyHomeController
  extends PhabricatorPolicyController {

  public function handleRequest(AphrontRequest $request) {
    $uri = $this->getApplicationURI('/named/');
    return id(new AphrontRedirectResponse())
      ->setURI($uri);
  }

}
