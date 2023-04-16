<?php

final class PhorgePolicyFavoritesSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'policy.favorites';

  public function getSettingName() {
    return pht('Policy Favorites');
  }

  public function getSettingDefaultValue() {
    return array();
  }

}
