<?php

final class PhorgePhurlURLSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'phurls.search';
  }

  public function newSearchEngine() {
    return new PhorgePhurlURLSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about Phurl URLS.');
  }

}
