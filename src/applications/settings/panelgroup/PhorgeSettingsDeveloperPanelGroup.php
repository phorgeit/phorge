<?php

final class PhorgeSettingsDeveloperPanelGroup
  extends PhorgeSettingsPanelGroup {

  const PANELGROUPKEY = 'developer';

  public function getPanelGroupName() {
    return pht('Developer');
  }

  protected function getPanelGroupOrder() {
    return 400;
  }

}
