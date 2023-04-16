<?php

final class PasteSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'paste.search';
  }

  public function newSearchEngine() {
    return new PhorgePasteSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about pastes.');
  }

}
