<?php

abstract class PhorgeFileController extends PhorgeController {

  public function buildApplicationMenu() {
    return $this->newApplicationMenu()
      ->setSearchEngine(new PhorgeFileSearchEngine());
  }

}
