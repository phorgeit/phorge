<?php

final class PhorgeSpacesNamespace
  extends PhorgeSpacesDAO
  implements
    PhorgePolicyInterface,
    PhorgeApplicationTransactionInterface,
    PhorgeDestructibleInterface {

  protected $namespaceName;
  protected $viewPolicy;
  protected $editPolicy;
  protected $isDefaultNamespace;
  protected $description;
  protected $isArchived;

  public static function initializeNewNamespace(PhorgeUser $actor) {
    $app = id(new PhorgeApplicationQuery())
      ->setViewer($actor)
      ->withClasses(array('PhorgeSpacesApplication'))
      ->executeOne();

    $view_policy = $app->getPolicy(
      PhorgeSpacesCapabilityDefaultView::CAPABILITY);
    $edit_policy = $app->getPolicy(
      PhorgeSpacesCapabilityDefaultEdit::CAPABILITY);

    return id(new PhorgeSpacesNamespace())
      ->setIsDefaultNamespace(null)
      ->setViewPolicy($view_policy)
      ->setEditPolicy($edit_policy)
      ->setDescription('')
      ->setIsArchived(0);
  }

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'namespaceName' => 'text255',
        'isDefaultNamespace' => 'bool?',
        'description' => 'text',
        'isArchived' => 'bool',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_default' => array(
          'columns' => array('isDefaultNamespace'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function generatePHID() {
    return PhorgePHID::generateNewPHID(
      PhorgeSpacesNamespacePHIDType::TYPECONST);
  }

  public function getMonogram() {
    return 'S'.$this->getID();
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

/* -(  PhorgeApplicationTransactionInterface  )------------------------- */


  public function getApplicationTransactionEditor() {
    return new PhorgeSpacesNamespaceEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeSpacesNamespaceTransaction();
  }


/* -(  PhorgeDestructibleInterface  )----------------------------------- */


  public function destroyObjectPermanently(
    PhorgeDestructionEngine $engine) {
    $this->delete();
  }

}
