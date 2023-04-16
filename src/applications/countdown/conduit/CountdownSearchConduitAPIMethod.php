<?php

final class CountdownSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'countdown.search';
  }

  public function newSearchEngine() {
    return new PhorgeCountdownSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about countdowns.');
  }

}
