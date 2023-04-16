<?php

final class PhorgePackagesPackageSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'packages.package.search';
  }

  public function newSearchEngine() {
    return new PhorgePackagesPackageSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about packages.');
  }

}
