<?php

final class PhabricatorAuthContactNumberEditController
  extends PhabricatorAuthContactNumberController {

  public function handleRequest(AphrontRequest $request) {
    $sms_auth_factor = new PhabricatorSMSAuthFactor();
    if ($sms_auth_factor->isSMSMailerConfigured()) {
      return id(new PhabricatorAuthContactNumberEditEngine())
        ->setController($this)
        ->buildResponse();
    } else {
      return new Aphront404Response();
    }
  }

}
