<?php

final class PhorgeDiffPreferencesSettingsPanel
  extends PhorgeEditEngineSettingsPanel {

  const PANELKEY = 'diff';

  public function getPanelName() {
    return pht('Diff Preferences');
  }

  public function getPanelMenuIcon() {
    return 'fa-cog';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsApplicationsPanelGroup::PANELGROUPKEY;
  }

  public function isTemplatePanel() {
    return true;
  }

}
