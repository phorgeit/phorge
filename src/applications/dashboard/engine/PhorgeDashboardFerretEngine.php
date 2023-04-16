<?php

final class PhorgeDashboardFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'dashboard';
  }

  public function getScopeName() {
    return 'dashboard';
  }

  public function newSearchEngine() {
    return new PhorgeDashboardSearchEngine();
  }

}
