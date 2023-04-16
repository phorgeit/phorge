<?php

final class PhorgeUserPreferencesEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeSettingsApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Settings');
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeUserPreferencesTransaction::TYPE_SETTING;

    return $types;
  }

  protected function expandTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $setting_key = $xaction->getMetadataValue(
      PhorgeUserPreferencesTransaction::PROPERTY_SETTING);

    $settings = $this->getSettings();
    $setting = idx($settings, $setting_key);
    if ($setting) {
      return $setting->expandSettingTransaction($object, $xaction);
    }

    return parent::expandTransaction($object, $xaction);
  }


  protected function getCustomTransactionOldValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $setting_key = $xaction->getMetadataValue(
      PhorgeUserPreferencesTransaction::PROPERTY_SETTING);

    switch ($xaction->getTransactionType()) {
      case PhorgeUserPreferencesTransaction::TYPE_SETTING:
        return $object->getPreference($setting_key);
    }

    return parent::getCustomTransactionOldValue($object, $xaction);
  }

  protected function getCustomTransactionNewValue(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $actor = $this->getActor();

    $setting_key = $xaction->getMetadataValue(
      PhorgeUserPreferencesTransaction::PROPERTY_SETTING);

    $settings = PhorgeSetting::getAllEnabledSettings($actor);
    $setting = $settings[$setting_key];

    switch ($xaction->getTransactionType()) {
      case PhorgeUserPreferencesTransaction::TYPE_SETTING:
        $value = $xaction->getNewValue();
        $value = $setting->getTransactionNewValue($value);
        return $value;
    }

    return parent::getCustomTransactionNewValue($object, $xaction);
  }

  protected function applyCustomInternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    $setting_key = $xaction->getMetadataValue(
      PhorgeUserPreferencesTransaction::PROPERTY_SETTING);

    switch ($xaction->getTransactionType()) {
      case PhorgeUserPreferencesTransaction::TYPE_SETTING:
        $new_value = $xaction->getNewValue();
        if ($new_value === null) {
          $object->unsetPreference($setting_key);
        } else {
          $object->setPreference($setting_key, $new_value);
        }
        return;
    }

    return parent::applyCustomInternalTransaction($object, $xaction);
  }

  protected function applyCustomExternalTransaction(
    PhorgeLiskDAO $object,
    PhorgeApplicationTransaction $xaction) {

    switch ($xaction->getTransactionType()) {
      case PhorgeUserPreferencesTransaction::TYPE_SETTING:
        return;
    }

    return parent::applyCustomExternalTransaction($object, $xaction);
  }

  protected function validateTransaction(
    PhorgeLiskDAO $object,
    $type,
    array $xactions) {

    $errors = parent::validateTransaction($object, $type, $xactions);
    $settings = $this->getSettings();

    switch ($type) {
      case PhorgeUserPreferencesTransaction::TYPE_SETTING:
        foreach ($xactions as $xaction) {
          $setting_key = $xaction->getMetadataValue(
            PhorgeUserPreferencesTransaction::PROPERTY_SETTING);

          $setting = idx($settings, $setting_key);
          if (!$setting) {
            $errors[] = new PhorgeApplicationTransactionValidationError(
              $type,
              pht('Invalid'),
              pht(
                'There is no known application setting with key "%s".',
                $setting_key),
              $xaction);
            continue;
          }

          try {
            $setting->validateTransactionValue($xaction->getNewValue());
          } catch (Exception $ex) {
            $errors[] = new PhorgeApplicationTransactionValidationError(
              $type,
              pht('Invalid'),
              $ex->getMessage(),
              $xaction);
          }
        }
        break;
    }

    return $errors;
  }

  protected function applyFinalEffects(
    PhorgeLiskDAO $object,
    array $xactions) {

    $user_phid = $object->getUserPHID();
    if ($user_phid) {
      PhorgeUserCache::clearCache(
        PhorgeUserPreferencesCacheType::KEY_PREFERENCES,
        $user_phid);
    } else {
      $cache = PhorgeCaches::getMutableStructureCache();
      $cache->deleteKey(PhorgeUser::getGlobalSettingsCacheKey());

      PhorgeUserCache::clearCacheForAllUsers(
        PhorgeUserPreferencesCacheType::KEY_PREFERENCES);
    }

    return $xactions;
  }

  private function getSettings() {
    $actor = $this->getActor();
    $settings = PhorgeSetting::getAllEnabledSettings($actor);

    foreach ($settings as $key => $setting) {
      $setting = clone $setting;
      $setting->setViewer($actor);
      $settings[$key] = $setting;
    }

    return $settings;
  }

}
