<?php

final class PhorgeExportFormatSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'export.format';

  public function getSettingName() {
    return pht('Export Format');
  }

  public function getSettingDefaultValue() {
    return null;
  }

}
