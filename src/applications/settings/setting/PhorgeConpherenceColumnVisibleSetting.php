<?php

final class PhorgeConpherenceColumnVisibleSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'conpherence-column';

  public function getSettingName() {
    return pht('Conpherence Column Visible');
  }

}
