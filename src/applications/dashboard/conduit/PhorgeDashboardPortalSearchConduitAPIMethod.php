<?php

final class PhorgeDashboardPortalSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'portal.search';
  }

  public function newSearchEngine() {
    return new PhorgeDashboardPortalSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about portals.');
  }

}
