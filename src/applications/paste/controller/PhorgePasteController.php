<?php

abstract class PhorgePasteController extends PhorgeController {

  public function buildApplicationMenu() {
    return $this->newApplicationMenu()
      ->setSearchEngine(new PhorgePasteSearchEngine());
  }

  public function buildSourceCodeView(
    PhorgePaste $paste,
    $highlights = array()) {

    $lines = phutil_split_lines($paste->getContent());

    return id(new PhorgeSourceCodeView())
      ->setLines($lines)
      ->setHighlights($highlights)
      ->setURI(new PhutilURI($paste->getURI()));
  }

}
