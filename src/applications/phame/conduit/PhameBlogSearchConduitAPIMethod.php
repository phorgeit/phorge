<?php

final class PhameBlogSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'phame.blog.search';
  }

  public function newSearchEngine() {
    return new PhameBlogSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about blogs.');
  }

}
