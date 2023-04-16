<?php

final class PhorgeFileSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'file.search';
  }

  public function newSearchEngine() {
    return new PhorgeFileSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about files.');
  }

}
