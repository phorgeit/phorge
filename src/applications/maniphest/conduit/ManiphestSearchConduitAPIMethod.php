<?php

final class ManiphestSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'maniphest.search';
  }

  public function newSearchEngine() {
    return new ManiphestTaskSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about tasks.');
  }

}
