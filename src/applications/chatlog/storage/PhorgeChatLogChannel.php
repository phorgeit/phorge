<?php

final class PhorgeChatLogChannel
  extends PhorgeChatLogDAO
  implements PhorgePolicyInterface {

  protected $serviceName;
  protected $serviceType;
  protected $channelName;
  protected $viewPolicy;
  protected $editPolicy;

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'serviceName' => 'text64',
        'serviceType' => 'text32',
        'channelName' => 'text64',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'key_channel' => array(
          'columns' => array('channelName', 'serviceType', 'serviceName'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
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
        return $this->viewPolicy;
        break;
      case PhorgePolicyCapability::CAN_EDIT:
        return $this->editPolicy;
        break;
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
