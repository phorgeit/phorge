<?php

final class PhorgeDiffusionBlameSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'diffusion-blame';

  public function getSettingName() {
    return pht('Diffusion Blame');
  }

  public function getSettingDefaultValue() {
    return false;
  }

}
