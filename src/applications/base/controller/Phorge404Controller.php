<?php

final class Phorge404Controller
  extends PhorgeController {

  public function shouldRequireLogin() {
    return false;
  }

  public function processRequest() {
    return new Aphront404Response();
  }

}
