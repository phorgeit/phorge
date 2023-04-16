<?php

final class PhorgeDisplayPreferencesSettingsPanel
  extends PhorgeEditEngineSettingsPanel {

  const PANELKEY = 'display';

  public function getPanelName() {
    return pht('Display Preferences');
  }

  public function getPanelMenuIcon() {
    return 'fa-desktop';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsApplicationsPanelGroup::PANELGROUPKEY;
  }

  public function isTemplatePanel() {
    return true;
  }

}
