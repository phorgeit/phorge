<?php

final class Phabricator404Controller
  extends PhabricatorController {

  public function shouldRequireLogin() {
    return false;
  }

  public function processRequest() {
    return new Aphront404Response();
  }

}
