<?php

final class PhorgeBadgesSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'badge.search';
  }

  public function newSearchEngine() {
    return new PhorgeBadgesSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about badges.');
  }

}
