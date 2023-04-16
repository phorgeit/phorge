<?php

final class PhorgePackagesVersionSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'packages.version.search';
  }

  public function newSearchEngine() {
    return new PhorgePackagesVersionSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about versions.');
  }

}
