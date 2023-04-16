<?php

final class PhorgeFiletreeWidthSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'filetree.width';

  public function getSettingName() {
    return pht('Filetree Width');
  }

}
