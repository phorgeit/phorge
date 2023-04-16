<?php

final class PhorgePackagesVersion
  extends PhorgePackagesDAO
  implements
    PhorgePolicyInterface,
    PhorgeExtendedPolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeDestructibleInterface,
    PhorgeSubscribableInterface,
    PhorgeProjectInterface,
    PhorgeConduitResultInterface,
    PhorgeNgramsInterface {

  protected $name;
  protected $packagePHID;

  private $package;

  public static function initializeNewVersion(PhorgeUser $actor) {
    return id(new self());
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_package' => array(
          'columns' => array('packagePHID', 'name'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePackagesVersionPHIDType::TYPECONST);
  }

  public function getURI() {
    $package = $this->getPackage();
    $full_key = $package->getFullKey();
    $name = $this->getName();

    return "/package/{$full_key}/{$name}/";
  }

  public function attachPackage(PhorgePackagesPackage $package) {
    $this->package = $package;
    return $this;
  }

  public function getPackage() {
    return $this->assertAttached($this->package);
  }

  public static function assertValidVersionName($value) {
    $length = phutil_utf8_strlen($value);
    if (!$length) {
      throw new Exception(
        pht(
          'Version name "%s" is not valid: version names are required.',
          $value));
    }

    $max_length = 64;
    if ($length > $max_length) {
      throw new Exception(
        pht(
          'Version name "%s" is not valid: version names must not be '.
          'more than %s characters long.',
          $value,
          new PhutilNumber($max_length)));
    }

    if (!preg_match('/^[A-Za-z0-9.-]+\z/', $value)) {
      throw new Exception(
        pht(
          'Version name "%s" is not valid: version names may only contain '.
          'latin letters, digits, periods, and hyphens.',
          $value));
    }

    if (preg_match('/^[.-]|[.-]$/', $value)) {
      throw new Exception(
        pht(
          'Version name "%s" is not valid: version names may not start or '.
          'end with a period or hyphen.',
          $value));
    }
  }


/* -(  PhorgeSubscribableInterface  )----------------------------------- */


  public function isAutomaticallySubscribed($phid) {
    return false;
  }


/* -(  Policy Interface  )--------------------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return PhorgePolicies::getMostOpenPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return PhorgePolicies::POLICY_USER;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $user) {
    return false;
  }


/* -(  PhorgeExtendedPolicyInterface  )--------------------------------- */


  public function getExtendedPolicy($capability, PhorgeUser $viewer) {
    return array(
      array(
        $this->getPackage(),
        $capability,
      ),
    );
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->delete();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgePackagesVersionEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgePackagesVersionTransaction();
  }


/* -(  PhorgeNgramsInterface  )----------------------------------------- */


  public function newNgrams() {
    return array(
      id(new PhorgePackagesVersionNameNgrams())
        ->setValue($this->getName()),
    );
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the version.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }


}
