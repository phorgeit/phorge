<?php

final class PhorgeNotificationsApplication extends PhorgeApplication {

  public function getName() {
    return pht('Notifications');
  }

  public function getBaseURI() {
    return '/notification/';
  }

  public function getShortDescription() {
    return pht('Real-Time Updates and Alerts');
  }

  public function getIcon() {
    return 'fa-bell';
  }

  public function getRoutes() {
    return array(
      '/notification/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgeNotificationListController',
        'panel/' => 'PhorgeNotificationPanelController',
        'individual/' => 'PhorgeNotificationIndividualController',
        'clear/' => 'PhorgeNotificationClearController',
        'test/' => 'PhorgeNotificationTestController',
      ),
    );
  }

  public function isLaunchable() {
    return false;
  }

}
