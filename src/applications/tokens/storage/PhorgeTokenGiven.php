<?php

final class PhorgeTokenGiven extends PhorgeTokenDAO
  implements PhorgePolicyInterface {

  protected $authorPHID;
  protected $objectPHID;
  protected $tokenPHID;

  private $object = self::ATTACHABLE;
  private $token = self::ATTACHABLE;

  protected function getConfiguration() {
    return array(
      self::CONFIG_KEY_SCHEMA => array(
        'key_all' => array(
          'columns' => array('objectPHID', 'authorPHID'),
          'unique' => true,
        ),
        'key_author' => array(
          'columns' => array('authorPHID'),
        ),
        'key_token' => array(
          'columns' => array('tokenPHID'),
        ),
      ),
    ) + parent::getConfiguration();
  }

  public function attachObject(PhorgeTokenReceiverInterface $object) {
    $this->object = $object;
    return $this;
  }

  public function getObject() {
    return $this->assertAttached($this->object);
  }

  public function attachToken(PhorgeToken $token) {
    $this->token = $token;
    return $this;
  }

  public function getToken() {
    return $this->assertAttached($this->token);
  }

  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
      PhorgePolicyCapability::CAN_EDIT,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return $this->getObject()->getPolicy($capability);
      default:
        return PhorgePolicies::POLICY_NOONE;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $user) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return $this->getObject()->hasAutomaticCapability(
          $capability,
          $user);
      default:
        if ($user->getPHID() == $this->authorPHID) {
          return true;
        }
        return false;
    }
  }

  public function describeAutomaticCapability($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return pht(
          'A token inherits the policies of the object it is awarded to.');
      case PhorgePolicyCapability::CAN_EDIT:
        return pht(
          'The user who gave a token can always edit it.');
    }
    return null;
  }


}
