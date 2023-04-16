<?php

abstract class PhorgeCountdownController extends PhorgeController {

  public function buildApplicationMenu() {
    return $this->newApplicationMenu()
      ->setSearchEngine(new PhorgeCountdownSearchEngine());
  }
}
