<?php

abstract class PhorgeAuthMessageController
  extends PhorgeAuthProviderController {

  protected function buildApplicationCrumbs() {
    return parent::buildApplicationCrumbs()
      ->addTextCrumb(pht('Messages'), $this->getApplicationURI('message/'));
  }

}
