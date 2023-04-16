<?php

final class PhorgeBadgesApplication extends PhorgeApplication {

  public function getName() {
    return pht('Badges');
  }

  public function getBaseURI() {
    return '/badges/';
  }

  public function getShortDescription() {
    return pht('Achievements and Notoriety');
  }

  public function getIcon() {
    return 'fa-trophy';
  }

  public function getFlavorText() {
    return pht('Build self esteem through gamification.');
  }

  public function getApplicationGroup() {
    return self::GROUP_UTILITIES;
  }

  public function getRoutes() {
    return array(
      '/badges/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgeBadgesListController',
        'award/(?:(?P<id>\d+)/)?'
          => 'PhorgeBadgesAwardController',
        'create/'
          => 'PhorgeBadgesEditController',
        'comment/(?P<id>[1-9]\d*)/'
          => 'PhorgeBadgesCommentController',
        $this->getEditRoutePattern('edit/')
            => 'PhorgeBadgesEditController',
        'archive/(?:(?P<id>\d+)/)?'
          => 'PhorgeBadgesArchiveController',
        'view/(?:(?P<id>\d+)/)?'
          => 'PhorgeBadgesViewController',
        'recipients/' => array(
          '(?P<id>[1-9]\d*)/'
            => 'PhorgeBadgesRecipientsController',
          '(?P<id>[1-9]\d*)/add/'
            => 'PhorgeBadgesEditRecipientsController',
          '(?P<id>[1-9]\d*)/remove/'
            => 'PhorgeBadgesRemoveRecipientsController',
        ),
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgeBadgesCreateCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
        'caption' => pht('Default create policy for badges.'),
      ),
      PhorgeBadgesDefaultEditCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
        'caption' => pht('Default edit policy for badges.'),
        'template' => PhorgeBadgesPHIDType::TYPECONST,
      ),
    );
  }

}
