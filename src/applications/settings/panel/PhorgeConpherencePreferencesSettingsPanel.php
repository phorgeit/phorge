<?php

final class PhorgeConpherencePreferencesSettingsPanel
  extends PhorgeEditEngineSettingsPanel {

  const PANELKEY = 'conpherence';

  public function getPanelName() {
    return pht('Conpherence');
  }

  public function getPanelMenuIcon() {
    return 'fa-comment-o';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsApplicationsPanelGroup::PANELGROUPKEY;
  }

  public function isTemplatePanel() {
    return true;
  }

}
