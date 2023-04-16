<?php

final class PhorgeDashboardPanelFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'dashboard';
  }

  public function getScopeName() {
    return 'panel';
  }

  public function newSearchEngine() {
    return new PhorgeDashboardPanelSearchEngine();
  }

}
