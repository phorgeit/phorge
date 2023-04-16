<?php

final class PhorgeEmailFormatSettingsPanel
  extends PhorgeEditEngineSettingsPanel {

  const PANELKEY = 'emailformat';

  public function getPanelName() {
    return pht('Email Format');
  }

  public function getPanelMenuIcon() {
    return 'fa-font';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsEmailPanelGroup::PANELGROUPKEY;
  }

  public function isUserPanel() {
    return PhorgeMetaMTAMail::shouldMailEachRecipient();
  }

  public function isManagementPanel() {
    return false;
  }

  public function isTemplatePanel() {
    return true;
  }

}
