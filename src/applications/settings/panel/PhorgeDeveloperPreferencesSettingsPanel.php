<?php

final class PhorgeDeveloperPreferencesSettingsPanel
  extends PhorgeEditEngineSettingsPanel {

  const PANELKEY = 'developer';

  public function getPanelName() {
    return pht('Developer Settings');
  }

  public function getPanelMenuIcon() {
    return 'fa-magic';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsDeveloperPanelGroup::PANELGROUPKEY;
  }

  public function isTemplatePanel() {
    return true;
  }

}
