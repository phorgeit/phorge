<?php

final class PhorgeDashboardPanelEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'dashboard.panel.edit';
  }

  public function newEditEngine() {
    return new PhorgeDashboardPanelEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new dashboard panel or edit an '.
      'existing one.');
  }

}
