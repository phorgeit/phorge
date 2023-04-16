<?php

abstract class PhorgeAuthFactorProviderController
  extends PhorgeAuthProviderController {

  protected function buildApplicationCrumbs() {
    return parent::buildApplicationCrumbs()
      ->addTextCrumb(pht('Multi-Factor'), $this->getApplicationURI('mfa/'));
  }

}
