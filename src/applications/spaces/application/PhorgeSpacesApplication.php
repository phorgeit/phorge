<?php

final class PhorgeSpacesApplication extends PhorgeApplication {

  public function getBaseURI() {
    return '/spaces/';
  }

  public function getName() {
    return pht('Spaces');
  }

  public function getShortDescription() {
    return pht('Policy Namespaces');
  }

  public function getIcon() {
    return 'fa-th-large';
  }

  public function getTitleGlyph() {
    return "\xE2\x97\x8B";
  }

  public function getFlavorText() {
    return pht('Control access to groups of objects.');
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function canUninstall() {
    return false;
  }

  public function getHelpDocumentationArticles(PhorgeUser $viewer) {
    return array(
      array(
        'name' => pht('Spaces User Guide'),
        'href' => PhorgeEnv::getDoclink('Spaces User Guide'),
      ),
    );
  }

  public function getRemarkupRules() {
    return array(
      new PhorgeSpacesRemarkupRule(),
    );
  }

  public function getRoutes() {
    return array(
      '/S(?P<id>[1-9]\d*)' => 'PhorgeSpacesViewController',
      '/spaces/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?' => 'PhorgeSpacesListController',
        'create/' => 'PhorgeSpacesEditController',
        'edit/(?:(?P<id>\d+)/)?' => 'PhorgeSpacesEditController',
        '(?P<action>activate|archive)/(?P<id>\d+)/'
          => 'PhorgeSpacesArchiveController',
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgeSpacesCapabilityCreateSpaces::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
      ),
      PhorgeSpacesCapabilityDefaultView::CAPABILITY => array(
        'caption' => pht('Default view policy for newly created spaces.'),
        'template' => PhorgeSpacesNamespacePHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_VIEW,
      ),
      PhorgeSpacesCapabilityDefaultEdit::CAPABILITY => array(
        'caption' => pht('Default edit policy for newly created spaces.'),
        'default' => PhorgePolicies::POLICY_ADMIN,
        'template' => PhorgeSpacesNamespacePHIDType::TYPECONST,
        'capability' => PhorgePolicyCapability::CAN_EDIT,
      ),
    );
  }

}
