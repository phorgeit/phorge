<?php

final class PhrictionContentSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'phriction.content.search';
  }

  public function newSearchEngine() {
    return new PhrictionContentSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about Phriction document history.');
  }

}
