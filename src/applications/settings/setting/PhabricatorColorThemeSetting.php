<?php

final class PhabricatorColorThemeSetting
  extends PhabricatorSelectSetting {

  const SETTINGKEY = 'resource-postprocessor';

  public function getSettingName() {
    return pht('Color Theme');
  }

  public function getSettingPanelKey() {
    return PhabricatorDisplayPreferencesSettingsPanel::PANELKEY;
  }

  protected function getSettingOrder() {
    return 100;
  }

  protected function getControlInstructions() {
    return pht(
      'Change the theme to high contrast or dark colors.');
  }

  public function getSettingDefaultValue() {
    return CelerityDefaultPostprocessor::POSTPROCESSOR_KEY;
  }

  protected function getSelectOptions() {
    $postprocessor_map = CelerityPostprocessor::getAllPostprocessors();

    $postprocessor_map = mpull($postprocessor_map, 'getPostprocessorName');
    asort($postprocessor_map);

    $postprocessor_order = array(
      CelerityDefaultPostprocessor::POSTPROCESSOR_KEY,
    );

    $postprocessor_map = array_select_keys(
      $postprocessor_map,
      $postprocessor_order) + $postprocessor_map;

    return $postprocessor_map;
  }

}
