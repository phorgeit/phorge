<?php

final class PhorgeTimezoneIgnoreOffsetSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'time.offset.ignore';

  public function getSettingName() {
    return pht('Timezone Ignored Offset');
  }

}
