<?php

final class PhorgeDashboardPortalFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'dashboard';
  }

  public function getScopeName() {
    return 'portal';
  }

  public function newSearchEngine() {
    return new PhorgeDashboardPortalSearchEngine();
  }

}
