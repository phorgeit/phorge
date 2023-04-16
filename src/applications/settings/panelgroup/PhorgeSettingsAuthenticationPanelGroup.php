<?php

final class PhorgeSettingsAuthenticationPanelGroup
  extends PhorgeSettingsPanelGroup {

  const PANELGROUPKEY = 'authentication';

  public function getPanelGroupName() {
    return pht('Authentication');
  }

  protected function getPanelGroupOrder() {
    return 300;
  }

}
