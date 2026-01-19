<?php

final class PhabricatorTranslationSetting
  extends PhabricatorOptionGroupSetting {

  const SETTINGKEY = 'translation';

  public function getSettingName() {
    return pht('Translation');
  }

  public function getSettingPanelKey() {
    return PhabricatorLanguageSettingsPanel::PANELKEY;
  }

  protected function getSettingOrder() {
    return 100;
  }

  public function getSettingDefaultValue() {
    return 'en_US';
  }

  protected function getControlInstructions() {
    return pht(
      'Choose which language you would like the UI to use.');
  }

  public function assertValidValue($value) {
    $locales = PhutilLocale::loadAllLocales();
    return isset($locales[$value]);
  }

  protected function getSelectOptionGroups() {
    // Loading translations for all locales and determining
    // whether they are limited can be expensive so cache it
    $cache = PhabricatorCaches::getRuntimeCache();
    $groups = $cache->getKey('locale.groups');
    if (!$groups) {
      $groups = $this->getLocaleGroups();
      $cache->setKey('locale.groups', $groups);
    }

    // These are done after the cache check so that changes to these config
    // settings via the web UI apply immediately

    // Omit silly locales on serious business installs.
    $is_serious = PhabricatorEnv::getEnvConfig('phabricator.serious-business');
    if ($is_serious) {
      unset($groups['silly']);
    }

    // Omit limited and test translations if Phabricator is not in developer
    // mode.
    $is_dev = PhabricatorEnv::getEnvConfig('phabricator.developer-mode');
    if (!$is_dev) {
      unset($groups['limited']);
      unset($groups['test']);
    }

    // This can't be in the cache since these pht calls
    // evaluate based on the locale of the current user
    $group_labels = array(
      'normal' => pht('Translations'),
      'limited' => pht('Limited Translations'),
      'silly' => pht('Silly Translations'),
      'test' => pht('Developer/Test Translations'),
    );
    $results = array();
    foreach ($groups as $key => $group) {
      $label = $group_labels[$key];
      if (!$group) {
        continue;
      }

      asort($group);

      $results[] = array(
        'label' => $label,
        'options' => $group,
      );
    }

    return $results;
  }

  private function getLocaleGroups() {
    $groups = array(
     'normal' => array(),
     'limited' => array(),
     'silly' => array(),
     'test' => array(),
    );
    $translations = PhutilTranslation::getAllTranslations();
    $locales = PhutilLocale::loadAllLocales();
    foreach ($locales as $locale) {
      $code = $locale->getLocaleCode();

      // Get the locale's localized name if it's available. For example,
      // "Deutsch" instead of "German". This helps users who do not speak the
      // current language to find the correct setting.
      // This also means that the locale name can be cached as it doesn't
      // vary on user settings.
      $raw_scope = PhabricatorEnv::beginScopedLocale($code);
      $name = $locale->getLocaleName();
      unset($raw_scope);

      if ($locale->isSillyLocale()) {
        $groups['silly'][$code] = $name;
        continue;
      }

      if ($locale->isTestLocale()) {
        $groups['test'][$code] = $name;
        continue;
      }

      if (empty($translations[$code])) {
        // Locales with zero translations are always "limited"
        // even if they are English, even if the fallback has some
        // (silly locales that post-process text rather than translating
        // like "ENGLISH (ALL CAPS)" are handled above)
        $groups['limited'][$code] = $name;
        continue;
      }

      // If a translation is English, assume it can fall back to the default
      // strings and don't caveat its completeness.
      if (substr($code, 0, 3) == 'en_') {
        $groups['normal'][$code] = $name;
        continue;
      }

      // Arbitrarily pick some number of available strings to promote a
      // translation out of the "limited" group. The major goal is just to
      // keep locales with very few strings out of the main group, so users
      // aren't surprised if a locale has no upstream translations available.
      $limited_max = 512;

      // Grab all fallbacks except the default fallback to en_US
      $current = $code;
      $strings = array();
      while ($current && $current != 'en_US') {
         $strings += $translations[$current];
         $fallbacks = $locales[$current]->getFallbackLocaleCode();
         if (is_array($fallbacks)) {
           foreach ($fallbacks as $fb) {
             if ($fb != 'en_US' && isset($translations[$fb])) {
               $strings += $translations[$fb];
             }
           }
           break;
        }
        $current = $fallbacks;
      }

      if (count($strings) > $limited_max) {
        $type = 'normal';
      } else {
        $type = 'limited';
      }

      $groups[$type][$code] = $name;
    }
    return $groups;
  }
}
