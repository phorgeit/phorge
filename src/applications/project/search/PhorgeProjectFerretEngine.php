<?php

final class PhorgeProjectFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'project';
  }

  public function getScopeName() {
    return 'project';
  }

  public function newSearchEngine() {
    return new PhorgeProjectSearchEngine();
  }

}
