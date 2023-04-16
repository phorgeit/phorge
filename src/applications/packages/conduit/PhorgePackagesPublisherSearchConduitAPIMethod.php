<?php

final class PhorgePackagesPublisherSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'packages.publisher.search';
  }

  public function newSearchEngine() {
    return new PhorgePackagesPublisherSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about publishers.');
  }

}
