<?php

final class PhorgeDashboardPortalEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'portal.edit';
  }

  public function newEditEngine() {
    return new PhorgeDashboardPortalEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new portal or edit an existing one.');
  }

}
