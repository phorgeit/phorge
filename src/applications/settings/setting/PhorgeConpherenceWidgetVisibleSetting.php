<?php

final class PhorgeConpherenceWidgetVisibleSetting
  extends PhorgeInternalSetting {

  const SETTINGKEY = 'conpherence-widget';

  public function getSettingName() {
    return pht('Conpherence Widget Pane Visible');
  }

}
