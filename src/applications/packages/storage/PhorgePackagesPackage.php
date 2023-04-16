<?php

final class PhorgePackagesPackage
  extends PhorgePackagesDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeDestructibleInterface,
    PhorgeSubscribableInterface,
    PhorgeProjectInterface,
    PhorgeConduitResultInterface,
    PhorgeNgramsInterface {

  protected $name;
  protected $publisherPHID;
  protected $packageKey;
  protected $viewPolicy;
  protected $editPolicy;

  private $publisher = self::ATTACHABLE;

  public static function initializeNewPackage(PhorgeUser $actor) {
    $packages_application = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgePackagesApplication'))
      ->executeOne();

    $view_policy = $packages_application->getPolicy(
      PhorgePackagesPackageDefaultViewCapability::CAPABILITY);

    $edit_policy = $packages_application->getPolicy(
      PhorgePackagesPackageDefaultEditCapability::CAPABILITY);

    return id(new self())
      ->setViewPolicy($view_policy)
      ->setEditPolicy($edit_policy);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort64',
        'packageKey' => 'sort64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_package' => array(
          'columns' => array('publisherPHID', 'packageKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePackagesPackagePHIDType::TYPECONST);
  }

  public function getURI() {
    $full_key = $this->getFullKey();
    return "/package/{$full_key}/";
  }

  public function getFullKey() {
    $publisher = $this->getPublisher();
    $publisher_key = $publisher->getPublisherKey();
    $package_key = $this->getPackageKey();
    return "{$publisher_key}/{$package_key}";
  }

  public function attachPublisher(PhorgePackagesPublisher $publisher) {
    $this->publisher = $publisher;
    return $this;
  }

  public function getPublisher() {
    return $this->assertAttached($this->publisher);
  }

  public static function assertValidPackageName($value) {
    $length = phutil_utf8_strlen($value);
    if (!$length) {
      throw new Exception(
        pht(
          'Package name "%s" is not valid: package names are required.',
          $value));
    }

    $max_length = 64;
    if ($length > $max_length) {
      throw new Exception(
        pht(
          'Package name "%s" is not valid: package names must not be '.
          'more than %s characters long.',
          $value,
          new PhutilNumber($max_length)));
    }
  }

  public static function assertValidPackageKey($value) {
    $length = phutil_utf8_strlen($value);
    if (!$length) {
      throw new Exception(
        pht(
          'Package key "%s" is not valid: package keys are required.',
          $value));
    }

    $max_length = 64;
    if ($length > $max_length) {
      throw new Exception(
        pht(
          'Package key "%s" is not valid: package keys must not be '.
          'more than %s characters long.',
          $value,
          new PhutilNumber($max_length)));
    }

    if (!preg_match('/^[a-z]+\z/', $value)) {
      throw new Exception(
        pht(
          'Package key "%s" is not valid: package keys may only contain '.
          'lowercase latin letters.',
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
        return $this->getViewPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->getEditPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $user) {
    return false;
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $viewer = $engine->getViewer();

    $this->openTransaction();

      $versions = id(new PhorgePackagesVersionQuery())
        ->setViewer($viewer)
        ->withPackagePHIDs(array($this->getPHID()))
        ->execute();
      foreach ($versions as $version) {
        $engine->destroyObject($version);
      }

      $this->delete();

    $this->saveTransaction();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgePackagesPackageEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgePackagesPackageTransaction();
  }


/* -(  PhorgeNgramsInterface  )----------------------------------------- */


  public function newNgrams() {
    return array(
      id(new PhorgePackagesPackageNameNgrams())
        ->setValue($this->getName()),
    );
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the package.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('packageKey')
        ->setType('string')
        ->setDescription(pht('The unique key of the package.')),
    );
  }

  public function getFieldValuesForConduit() {
    $publisher = $this->getPublisher();

    $publisher_map = array(
      'id' => (int)$publisher->getID(),
      'phid' => $publisher->getPHID(),
      'name' => $publisher->getName(),
      'publisherKey' => $publisher->getPublisherKey(),
    );

    return array(
      'name' => $this->getName(),
      'packageKey' => $this->getPackageKey(),
      'fullKey' => $this->getFullKey(),
      'publisher' => $publisher_map,
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }


}
