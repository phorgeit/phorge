<?php

final class SlowvoteSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'slowvote.poll.search';
  }

  public function newSearchEngine() {
    return new PhorgeSlowvoteSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about polls.');
  }

}
