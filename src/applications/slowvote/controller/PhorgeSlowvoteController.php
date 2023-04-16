<?php

abstract class PhorgeSlowvoteController extends PhorgeController {

  public function buildApplicationMenu() {
    return $this->newApplicationMenu()
      ->setSearchEngine(new PhorgeSlowvoteSearchEngine());
  }

}
