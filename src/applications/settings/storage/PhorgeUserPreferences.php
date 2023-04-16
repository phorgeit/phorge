<?php

final class PhorgeUserPreferences
  extends PhorgeUserDAO
  implements
    PhorgePolicyInterface,
    PhorgeDestructibleInterface,
    PhorgeApplicationTransactionInterface {

  const BUILTIN_GLOBAL_DEFAULT = 'global';

  protected $userPHID;
  protected $preferences = array();
  protected $builtinKey;

  private $user = self::ATTACHABLE;
  private $defaultSettings;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'preferences' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'userPHID' => 'phid?',
        'builtinKey' => 'text32?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_user' => array(
          'columns' => array('userPHID'),
          'unique' => true,
        ),
        'key_builtin' => array(
          'columns' => array('builtinKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeUserPreferencesPHIDType::TYPECONST);
  }

  public function getPreference($key, $default = null) {
    return idx($this->preferences, $key, $default);
  }

  public function setPreference($key, $value) {
    $this->preferences[$key] = $value;
    return $this;
  }

  public function unsetPreference($key) {
    unset($this->preferences[$key]);
    return $this;
  }

  public function getDefaultValue($key) {
    if ($this->defaultSettings) {
      return $this->defaultSettings->getSettingValue($key);
    }

    $setting = self::getSettingObject($key);

    if (!$setting) {
      return null;
    }

    $setting = id(clone $setting)
      ->setViewer($this->getUser());

    return $setting->getSettingDefaultValue();
  }

  public function getSettingValue($key) {
    if (array_key_exists($key, $this->preferences)) {
      return $this->preferences[$key];
    }

    return $this->getDefaultValue($key);
  }

  private static function getSettingObject($key) {
    $settings = PhorgeSetting::getAllSettings();
    return idx($settings, $key);
  }

  public function attachDefaultSettings(PhorgeUserPreferences $settings) {
    $this->defaultSettings = $settings;
    return $this;
  }

  public function attachUser(PhorgeUser $user = null) {
    $this->user = $user;
    return $this;
  }

  public function getUser() {
    return $this->assertAttached($this->user);
  }

  public function hasManagedUser() {
    $user_phid = $this->getUserPHID();
    if (!$user_phid) {
      return false;
    }

    $user = $this->getUser();
    if ($user->getIsSystemAgent() || $user->getIsMailingList()) {
      return true;
    }

    return false;
  }

  /**
   * Load or create a preferences object for the given user.
   *
   * @param PhorgeUser User to load or create preferences for.
   */
  public static function loadUserPreferences(PhorgeUser $user) {
    return id(new PhorgeUserPreferencesQuery())
      ->setViewer($user)
      ->withUsers(array($user))
      ->needSyntheticPreferences(true)
      ->executeOne();
  }

  /**
   * Load or create a global preferences object.
   *
   * If no global preferences exist, an empty preferences object is returned.
   *
   * @param PhorgeUser Viewing user.
   */
  public static function loadGlobalPreferences(PhorgeUser $viewer) {
    $global = id(new PhorgeUserPreferencesQuery())
      ->setViewer($viewer)
      ->withBuiltinKeys(
        array(
          self::BUILTIN_GLOBAL_DEFAULT,
        ))
      ->executeOne();

    if (!$global) {
      $global = id(new self())
        ->attachUser(new PhorgeUser());
    }

    return $global;
  }

  public function newTransaction($key, $value) {
    $setting_property = PhorgeUserPreferencesTransaction::PROPERTY_SETTING;
    $xaction_type = PhorgeUserPreferencesTransaction::TYPE_SETTING;

    return id(clone $this->getApplicationTransactionTemplate())
      ->setTransactionType($xaction_type)
      ->setMetadataValue($setting_property, $key)
      ->setNewValue($value);
  }

  public function getEditURI() {
    if ($this->getUser()) {
      return '/settings/user/'.$this->getUser()->getUsername().'/';
    } else {
      return '/settings/builtin/'.$this->getBuiltinKey().'/';
    }
  }

  public function getDisplayName() {
    if ($this->getBuiltinKey()) {
      return pht('Global Default Settings');
    }

    return pht('Personal Settings');
  }

/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        $user_phid = $this->getUserPHID();
        if ($user_phid) {
          return $user_phid;
        }

        return PhorgePolicies::getMostOpenPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        if ($this->hasManagedUser()) {
          return PhorgePolicies::POLICY_ADMIN;
        }

        $user_phid = $this->getUserPHID();
        if ($user_phid) {
          return $user_phid;
        }

        return PhorgePolicies::POLICY_ADMIN;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    if ($this->hasManagedUser()) {
      if ($viewer->getIsAdmin()) {
        return true;
      }
    }

    $builtin_key = $this->getBuiltinKey();

    $is_global = ($builtin_key === self::BUILTIN_GLOBAL_DEFAULT);
    $is_view = ($capability === PhorgePolicyCapability::CAN_VIEW);

    if ($is_global && $is_view) {
      // NOTE: Without this policy exception, the logged-out viewer can not
      // see global preferences.
      return true;
    }

    return false;
  }

/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->delete();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeUserPreferencesEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeUserPreferencesTransaction();
  }

}
