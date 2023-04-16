<?php

final class PhorgeDateTimeSettingsPanel
  extends PhorgeEditEngineSettingsPanel {

  const PANELKEY = 'datetime';

  public function getPanelName() {
    return pht('Date and Time');
  }

  public function getPanelMenuIcon() {
    return 'fa-calendar';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsAccountPanelGroup::PANELGROUPKEY;
  }

  public function isManagementPanel() {
    return true;
  }

  public function isTemplatePanel() {
    return true;
  }

}
