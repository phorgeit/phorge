<?php

final class PhorgeDarkConsoleVisibleSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'darkconsole.visible';

  public function getSettingName() {
    return pht('DarkConsole Visible');
  }

}
