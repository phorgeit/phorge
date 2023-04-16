<?php

final class PhorgeUserFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'user';
  }

  public function getScopeName() {
    return 'user';
  }

  public function newSearchEngine() {
    return new PhorgePeopleSearchEngine();
  }

  public function getObjectTypeRelevance() {
    // Always sort users above other documents, regardless of relevance
    // metrics. A user profile is very likely to be the best hit for a query
    // which matches a user.
    return 500;
  }

}
