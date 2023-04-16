<?php

final class PhorgeOwnersPackageFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'owners';
  }

  public function getScopeName() {
    return 'package';
  }

  public function newSearchEngine() {
    return new PhorgeOwnersPackageSearchEngine();
  }

}
