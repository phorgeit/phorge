<?php

final class RedirectOldRemarkupReferenceController
  extends PhabricatorController {

  public function handleRequest(AphrontRequest $request) {
    return id(new AphrontRedirectResponse())
      ->setUri('/remarkup/');
  }

}
