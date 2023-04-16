<?php

final class PhorgeSettingsLogsPanelGroup
  extends PhorgeSettingsPanelGroup {

  const PANELGROUPKEY = 'logs';

  public function getPanelGroupName() {
    return pht('Sessions and Logs');
  }

  protected function getPanelGroupOrder() {
    return 600;
  }

}
