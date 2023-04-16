<?php

final class PhorgeConpherenceColumnMinimizeSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'conpherence-minimize-column';

  public function getSettingName() {
    return pht('Conpherence Column Minimize');
  }

}
