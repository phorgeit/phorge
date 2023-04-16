<?php

final class PhorgeFiletreeVisibleSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'nav-collapsed';

  public function getSettingName() {
    return pht('Filetree Visible');
  }

}
