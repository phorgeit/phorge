<?php

final class PhorgeEmailDeliverySettingsPanel
  extends PhorgeEditEngineSettingsPanel {

  const PANELKEY = 'emaildelivery';

  public function getPanelName() {
    return pht('Email Delivery');
  }

  public function getPanelMenuIcon() {
    return 'fa-envelope-o';
  }

  public function getPanelGroupKey() {
    return PhorgeSettingsEmailPanelGroup::PANELGROUPKEY;
  }

  public function isManagementPanel() {
    if ($this->getUser()->getIsMailingList()) {
      return true;
    }

    return false;
  }

  public function isTemplatePanel() {
    return true;
  }

}
