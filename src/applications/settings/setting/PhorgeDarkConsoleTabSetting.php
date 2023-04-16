<?php

final class PhorgeDarkConsoleTabSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'darkconsole.tab';

  public function getSettingName() {
    return pht('DarkConsole Tab');
  }

}
