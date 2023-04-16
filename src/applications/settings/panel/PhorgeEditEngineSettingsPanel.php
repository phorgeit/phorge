<?php

abstract class PhorgeEditEngineSettingsPanel
  extends PhorgeSettingsPanel {

  final public function processRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();
    $user = $this->getUser();

    if ($user && ($user->getPHID() === $viewer->getPHID())) {
      $is_self = true;
    } else {
      $is_self = false;
    }

    if ($user && $user->getPHID()) {
      $profile_uri = '/people/manage/'.$user->getID().'/';
    } else {
      $profile_uri = null;
    }

    $engine = id(new PhorgeSettingsEditEngine())
      ->setController($this->getController())
      ->setNavigation($this->getNavigation())
      ->setSettingsPanel($this)
      ->setIsSelfEdit($is_self)
      ->setProfileURI($profile_uri);

    $preferences = $this->getPreferences();

    $engine->setTargetObject($preferences);

    return $engine->buildResponse();
  }

  final public function isEnabled() {
    // Only enable the panel if it has any fields.
    $field_keys = $this->getPanelSettingsKeys();
    return (bool)$field_keys;
  }

  final public function newEditEnginePage() {
    $field_keys = $this->getPanelSettingsKeys();
    if (!$field_keys) {
      return null;
    }

    $key = $this->getPanelKey();
    $label = $this->getPanelName();
    $panel_uri = $this->getPanelURI();

    return id(new PhorgeEditPage())
      ->setKey($key)
      ->setLabel($label)
      ->setViewURI($panel_uri)
      ->setFieldKeys($field_keys);
  }

  final public function getPanelSettingsKeys() {
    $viewer = $this->getViewer();
    $settings = PhorgeSetting::getAllEnabledSettings($viewer);

    $this_key = $this->getPanelKey();

    $panel_settings = array();
    foreach ($settings as $setting) {
      if ($setting->getSettingPanelKey() == $this_key) {
        $panel_settings[] = $setting;
      }
    }

    return mpull($panel_settings, 'getSettingKey');
  }

  public function newSettingsPanelEditFormHeadContent(
    PhorgeEditEnginePageState $state) {
    return null;
  }

  public function newSettingsPanelEditFormTailContent(
    PhorgeEditEnginePageState $state) {
    return null;
  }

}
