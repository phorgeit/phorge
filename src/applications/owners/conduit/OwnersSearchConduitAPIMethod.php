<?php

final class OwnersSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'owners.search';
  }

  public function newSearchEngine() {
    return new PhorgeOwnersPackageSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about Owners packages.');
  }

}
