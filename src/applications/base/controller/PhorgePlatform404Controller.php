<?php

final class PhorgePlatform404Controller
  extends PhorgeController {

  public function processRequest() {
    return new Aphront404Response();
  }

}
