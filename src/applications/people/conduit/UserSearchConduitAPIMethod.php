<?php

final class UserSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'user.search';
  }

  public function newSearchEngine() {
    return new PhorgePeopleSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about users.');
  }

}
