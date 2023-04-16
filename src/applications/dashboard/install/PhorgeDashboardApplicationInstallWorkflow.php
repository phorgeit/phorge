<?php

abstract class PhorgeDashboardApplicationInstallWorkflow
  extends PhorgeDashboardInstallWorkflow {

  abstract protected function newApplication();

  protected function canInstallToGlobalMenu() {
    return PhorgePolicyFilter::hasCapability(
      $this->getViewer(),
      $this->newApplication(),
      PhorgePolicyCapability::CAN_EDIT);
  }

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $application = $this->newApplication();
    $can_global = $this->canInstallToGlobalMenu();

    switch ($this->getMode()) {
      case 'global':
        if (!$can_global) {
          return $this->newGlobalPermissionDialog();
        } else if ($request->isFormPost()) {
          return $this->installDashboard($application, null);
        } else {
          return $this->newGlobalConfirmDialog();
        }
      case 'personal':
        if ($request->isFormPost()) {
          return $this->installDashboard($application, $viewer->getPHID());
        } else {
          return $this->newPersonalConfirmDialog();
        }
    }

    $global_item = $this->newGlobalMenuItem()
      ->setDisabled(!$can_global);

    $menu = $this->newMenuFromItemMap(
      array(
        'personal' => $this->newPersonalMenuItem(),
        'global' => $global_item,
      ));

    return $this->newApplicationModeDialog()
      ->appendChild($menu);
  }

  abstract protected function newGlobalPermissionDialog();
  abstract protected function newGlobalConfirmDialog();
  abstract protected function newPersonalConfirmDialog();

  abstract protected function newPersonalMenuItem();
  abstract protected function newGlobalMenuItem();
  abstract protected function newApplicationModeDialog();

}
