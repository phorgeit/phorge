<?php

final class PhorgePhurlShortURLController
  extends PhorgePhurlController {

  public function shouldRequireLogin() {
    return false;
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $append = $request->getURIData('append');
    $main_domain_uri = PhorgeEnv::getProductionURI('/u/'.$append);

    return id(new AphrontRedirectResponse())
      ->setIsExternal(true)
      ->setURI($main_domain_uri);
  }
}
