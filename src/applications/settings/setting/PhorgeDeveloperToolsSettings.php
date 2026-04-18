<?php

final class PhorgeDeveloperToolsSettings extends PhabricatorSelectSetting {

  const SETTINGKEY = 'developer_tools';

  const VALUE_DEVELOPERTOOLS_DISABLED = '0';
  const VALUE_DEVELOPERTOOLS_ENABLED = '1';

  public function getSettingName() {
    return pht('Developer Tools');
  }

  public function getSettingPanelKey() {
    return PhabricatorDeveloperPreferencesSettingsPanel::PANELKEY;
  }

  protected function getSettingOrder() {
    return 200;
  }

  protected function getControlInstructions() {
    return pht(
      'Developer Tools show more tools that are mostly useful for '.
      '%s developers and advanced administrators.',
      PlatformSymbols::getPlatformServerName());
  }


  protected function getSelectOptions() {
    return array(
      self::VALUE_DEVELOPERTOOLS_DISABLED => pht('Disable Developer Tools'),
      self::VALUE_DEVELOPERTOOLS_ENABLED => pht('Enable Developer Tools'),
    );
  }

  public function getSettingDefaultValue() {
    return self::VALUE_DEVELOPERTOOLS_DISABLED;
  }

}
