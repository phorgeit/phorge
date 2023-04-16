<?php

final class PhorgeSearchSettingsPanel
  extends PhorgeEditEngineSettingsPanel {

  const PANELKEY = 'search';

  public function getPanelName() {
    return pht('Search');
  }

  public function getPanelMenuIcon() {
    return 'fa-search';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsApplicationsPanelGroup::PANELGROUPKEY;
  }

  public function isTemplatePanel() {
    return true;
  }

  public function isUserPanel() {
    return false;
  }

}
