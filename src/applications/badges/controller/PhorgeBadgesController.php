<?php

abstract class PhorgeBadgesController extends PhorgeController {

  public function buildApplicationMenu() {
    return $this->newApplicationMenu()
      ->setSearchEngine(new PhorgeBadgesSearchEngine());
  }

}
