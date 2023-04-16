<?php

final class PhrictionDocumentSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'phriction.document.search';
  }

  public function newSearchEngine() {
    return new PhrictionDocumentSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about Phriction documents.');
  }

}
