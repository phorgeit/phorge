<?php

final class PhorgeCountdownApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/countdown/';
  }

  public function getIcon() {
    return 'fa-rocket';
  }

  public function getName() {
    return pht('Countdown');
  }

  public function getShortDescription() {
    return pht('Countdown to Events');
  }

  public function getTitleGlyph() {
    return "\xE2\x9A\xB2";
  }

  public function getFlavorText() {
    return pht('Utilize the full capabilities of your ALU.');
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function getRemarkupRules() {
    return array(
      new PhorgeCountdownRemarkupRule(),
    );
  }

  public function getRoutes() {
    return array(
      '/C(?P<id>[1-9]\d*)' => 'PhorgeCountdownViewController',
      '/countdown/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgeCountdownListController',
        $this->getEditRoutePattern('edit/')
          => 'PhorgeCountdownEditController',
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgeCountdownDefaultViewCapability::CAPABILITY => array(
        'caption' => pht('Default view policy for new countdowns.'),
        'template' => PhorgeCountdownCountdownPHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_VIEW,
      ),
      PhorgeCountdownDefaultEditCapability::CAPABILITY => array(
        'caption' => pht('Default edit policy for new countdowns.'),
        'template' => PhorgeCountdownCountdownPHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_EDIT,
      ),
    );
  }

}
