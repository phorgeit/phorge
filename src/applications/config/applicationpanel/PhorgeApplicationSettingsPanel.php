<?php

final class PhorgeApplicationSettingsPanel
  extends PhabricatorApplicationConfigurationPanel {

  public function getPanelKey() {
    return 'settings';
  }

  public function shouldShowForApplication($application) {
    return true;
  }

  public function buildConfigurationPagePanel() {
    $viewer = $this->getViewer();

    $box = id(new PHUIObjectBoxView())
      ->setHeaderText(pht('Settings'));

    if (!$viewer->getIsAdmin()) {
      $view = id(new PHUIInfoView())
        ->setSeverity(PHUIInfoView::SEVERITY_PLAIN)
        ->setErrors(array(pht('Settings are only shown to Administrators.')));
      $box->setInfoView($view);
      return $box;
    }

    $application_key = get_class($this->getApplication());

    $options =
      PhabricatorApplicationConfigOptions::loadOptionsForApplications(
        array($application_key));

    $list = id(new PhorgeConfigOptionListView())
      ->setViewer($viewer)
      ->setOptions($options)
      ->setAppKey($application_key);

    $box->setObjectList($list);

    return $box;
  }

}
