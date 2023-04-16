<?php

final class PhorgeSettingsApplicationsPanelGroup
  extends PhorgeSettingsPanelGroup {

  const PANELGROUPKEY = 'applications';

  public function getPanelGroupName() {
    return pht('Applications');
  }

  protected function getPanelGroupOrder() {
    return 200;
  }

}
