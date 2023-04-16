<?php

final class PhorgeToken extends PhorgeTokenDAO
  implements PhorgePolicyInterface {

  protected $phid;
  protected $name;
  protected $filePHID;

  protected function getConfiguration() {
    return array(
      self::CONFIG_AUX_PHID => true,
      self::CONFIG_NO_TABLE => true,
    ) + parent::getConfiguration();
  }

  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    return PhorgePolicies::getMostOpenPolicy();
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

  public function renderIcon() {
    // TODO: Maybe move to a View class?

    require_celerity_resource('sprite-tokens-css');
    require_celerity_resource('tokens-css');

    $sprite = substr($this->getPHID(), 10);

    return id(new PHUIIconView())
      ->setSpriteSheet(PHUIIconView::SPRITE_TOKENS)
      ->setSpriteIcon($sprite);

  }

}
