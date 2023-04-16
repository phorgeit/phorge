<?php

final class PhorgeRepositoryFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'repository';
  }

  public function getScopeName() {
    return 'repository';
  }

  public function newSearchEngine() {
    return new PhorgeRepositorySearchEngine();
  }

}
