<?php

final class PhorgeNamedPolicy
  extends PhabricatorPolicyDAO
  implements
    PhabricatorApplicationTransactionInterface,
    PhabricatorPolicyInterface,
    PhabricatorSubscribableInterface,

    PhabricatorDestructibleInterface {

  protected $effectivePolicy = '';
  protected $name;
  protected $description = '';
  protected $viewPolicy = PhabricatorPolicies::POLICY_USER;
  protected $editPolicy = PhabricatorPolicies::POLICY_USER;
  protected $targetObjectType;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'sort255',
        'description' => 'text',
        'targetObjectType' => 'text8?',
        'effectivePolicy' => 'policy',
      ),
    ) + parent::getConfiguration();
  }


  public function getPHIDType() {
    return PhorgePolicyPHIDTypeNamedPolicy::TYPECONST;
  }

  public function getHref() {
    return "/policy/named/{$this->getID()}/";
  }

  public function getIcon() {
    return 'fa-lock';
  }

  public function getReferenceObject() {
    if ($this->targetObjectType) {
      $type_object = $this->getReferenceObjectPHIDType();
      $object = null;
      if ($type_object) {
        $object = $type_object->newObject();
      }
      if ($object instanceof PhabricatorPolicyInterface) {
        return $object;
      }
      phlog(
        pht(
          'Failed to create usable reference object for named policy %s - '.
          'target type is %s',
          $this->getPHID(),
          $this->targetObjectType));
    }
    // This object implements PhabricatorPolicyInterface and nothing else:
    return new PhabricatorPolicy();
  }

  public function getReferenceObjectPHIDType() {
    if ($this->targetObjectType) {
      $type_objects = PhabricatorPHIDType::getTypes(
        array($this->targetObjectType));
      return idx($type_objects, $this->targetObjectType);
    }
    return null;
  }

  public function canApplyToObject(PhabricatorPolicyInterface $object) {
    if (!$this->targetObjectType) {
      return true;
    }

    $phid = $object->getPHID();
    if ($phid != null) {
      return phid_get_type($phid) == $this->targetObjectType;
    }

    // when everything implements getTypeConstant(), this would be simpler
    $ref_type = $this->getReferenceObjectPHIDType();
    if (!$ref_type) {
      // invalid value for targetObjectType
      return true;
    }
    $ref_object = $ref_type->newObject();
    if ($ref_object == null) {
      // Very rare
      return true;
    }
    return get_class($ref_object) === get_class($object);
  }

/* -(  PhabricatorPolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhabricatorPolicyCapability::CAN_VIEW,
      PhabricatorPolicyCapability::CAN_EDIT,
      PhorgeNamedPolicyEffectivePolicyCapability::CAPABILITY,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhabricatorPolicyCapability::CAN_VIEW:
        return $this->getViewPolicy();
      case PhabricatorPolicyCapability::CAN_EDIT:
        return $this->getEditPolicy();
      case PhorgeNamedPolicyEffectivePolicyCapability::CAPABILITY:
        return $this->getEffectivePolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhabricatorUser $viewer) {
    return false;
  }


/* -(  PhabricatorApplicationTransactionInterface  )------------------------- */

  public function getApplicationTransactionEditor() {
    return new PhorgePolicyNamedPolicyTransactionEditor();
  }

  public function getApplicationTransactionTemplate() {
    return new PhorgeNamedPolicyTransaction();
  }


/* -(  PhabricatorSubscribableInterface  )----------------------------------- */

  public function isAutomaticallySubscribed($phid) {
    return false;
  }

/* -(  PhabricatorDestructibleInterface  )----------------------------------- */

  public function destroyObjectPermanently(
    PhabricatorDestructionEngine $engine) {

    $this->delete();

  }


}
