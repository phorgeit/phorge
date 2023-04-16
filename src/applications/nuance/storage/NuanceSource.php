<?php

final class NuanceSource extends NuanceDAO
  implements
    PhorgeApplicationTransactionInterface,
    PhorgePolicyInterface,
    PhorgeNgramsInterface {

  protected $name;
  protected $type;
  protected $data = array();
  protected $mailKey;
  protected $viewPolicy;
  protected $editPolicy;
  protected $defaultQueuePHID;
  protected $isDisabled;

  private $definition = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_SERIALIZATION => array(
        'data' => self::SERIALIZATION_JSON,
      ),
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort255',
        'type' => 'text32',
        'mailKey' => 'bytes20',
        'isDisabled' => 'bool',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_type' => array(
          'columns' => array('type', 'dateModified'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(NuanceSourcePHIDType::TYPECONST);
  }

  public function save() {
    if (!$this->getMailKey()) {
      $this->setMailKey(Filesystem::readRandomCharacters(20));
    }
    return parent::save();
  }

  public function getURI() {
    return '/nuance/source/view/'.$this->getID().'/';
  }

  public static function initializeNewSource(
    PhorgeUser $actor,
    NuanceSourceDefinition $definition) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgeNuanceApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(
      NuanceSourceDefaultViewCapability::CAPABILITY);
    $edit_policy = $app->getPolicy(
      NuanceSourceDefaultEditCapability::CAPABILITY);

    return id(new NuanceSource())
      ->setViewPolicy($view_policy)
      ->setEditPolicy($edit_policy)
      ->setType($definition->getSourceTypeConstant())
      ->attachDefinition($definition)
      ->setIsDisabled(0);
  }

  public function getDefinition() {
    return $this->assertAttached($this->definition);
  }

  public function attachDefinition(NuanceSourceDefinition $definition) {
    $this->definition = $definition;
    return $this;
  }

  public function getSourceProperty($key, $default = null) {
    return idx($this->data, $key, $default);
  }

  public function setSourceProperty($key, $value) {
    $this->data[$key] = $value;
    return $this;
  }


/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new NuanceSourceEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new NuanceSourceTransaction();
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
        return $this->getViewPolicy();
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->getEditPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }


/* -(  PhorgeNgramsInterface  )----------------------------------------- */


  public function newNgrams() {
    return array(
      id(new NuanceSourceNameNgrams())
        ->setValue($this->getName()),
    );
  }

}
