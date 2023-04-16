<?php

final class PhorgePackagesPublisher
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
  protected $publisherKey;
  protected $editPolicy;

  public static function initializeNewPublisher(PhorgeUser $actor) {
    $packages_application = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgePackagesApplication'))
      ->executeOne();

    $edit_policy = $packages_application->getPolicy(
      PhorgePackagesPublisherDefaultEditCapability::CAPABILITY);

    return id(new self())
      ->setEditPolicy($edit_policy);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort64',
        'publisherKey' => 'sort64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_publisher' => array(
          'columns' => array('publisherKey'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgePackagesPublisherPHIDType::TYPECONST);
  }

  public function getURI() {
    $publisher_key = $this->getPublisherKey();
    return "/package/{$publisher_key}/";
  }

  public static function assertValidPublisherName($value) {
    $length = phutil_utf8_strlen($value);
    if (!$length) {
      throw new Exception(
        pht(
          'Publisher name "%s" is not valid: publisher names are required.',
          $value));
    }

    $max_length = 64;
    if ($length > $max_length) {
      throw new Exception(
        pht(
          'Publisher name "%s" is not valid: publisher names must not be '.
          'more than %s characters long.',
          $value,
          new PhutilNumber($max_length)));
    }
  }

  public static function assertValidPublisherKey($value) {
    $length = phutil_utf8_strlen($value);
    if (!$length) {
      throw new Exception(
        pht(
          'Publisher key "%s" is not valid: publisher keys are required.',
          $value));
    }

    $max_length = 64;
    if ($length > $max_length) {
      throw new Exception(
        pht(
          'Publisher key "%s" is not valid: publisher keys must not be '.
          'more than %s characters long.',
          $value,
          new PhutilNumber($max_length)));
    }

    if (!preg_match('/^[a-z]+\z/', $value)) {
      throw new Exception(
        pht(
          'Publisher key "%s" is not valid: publisher keys may only contain '.
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
        return PhorgePolicies::getMostOpenPolicy();
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

      $packages = id(new PhorgePackagesPackageQuery())
        ->setViewer($viewer)
        ->withPublisherPHIDs(array($this->getPHID()))
        ->execute();
      foreach ($packages as $package) {
        $engine->destroyObject($package);
      }

      $this->delete();

    $this->saveTransaction();
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgePackagesPublisherEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgePackagesPublisherTransaction();
  }


/* -(  PhorgeNgramsInterface  )----------------------------------------- */


  public function newNgrams() {
    return array(
      id(new PhorgePackagesPublisherNameNgrams())
        ->setValue($this->getName()),
    );
  }


/* -(  PhorgeConduitResultInterface  )---------------------------------- */


  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the publisher.')),
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('publisherKey')
        ->setType('string')
        ->setDescription(pht('The unique key of the publisher.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
      'publisherKey' => $this->getPublisherKey(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }


}
