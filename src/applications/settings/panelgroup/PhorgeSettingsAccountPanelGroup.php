<?php

final class PhorgeSettingsAccountPanelGroup
  extends PhorgeSettingsPanelGroup {

  const PANELGROUPKEY = 'account';

  public function getPanelGroupName() {
    return pht('Account');
  }

  protected function getPanelGroupOrder() {
    return 100;
  }

}
