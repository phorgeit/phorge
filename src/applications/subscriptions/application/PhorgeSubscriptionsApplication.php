<?php

final class PhorgeSubscriptionsApplication extends PhorgeApplication {

  public function getName() {
    return pht('Subscriptions');
  }

  public function isLaunchable() {
    return false;
  }

  public function canUninstall() {
    return false;
  }

  public function getEventListeners() {
    return array(
      new PhorgeSubscriptionsUIEventListener(),
    );
  }

  public function getRoutes() {
    return array(
      '/subscriptions/' => array(
        '(?P<action>add|delete)/'.
          '(?P<phid>[^/]+)/' => 'PhorgeSubscriptionsEditController',
        'mute/' => array(
          '(?P<phid>[^/]+)/' => 'PhorgeSubscriptionsMuteController',
        ),
        'list/(?P<phid>[^/]+)/' => 'PhorgeSubscriptionsListController',
        'transaction/(?P<type>add|rem)/(?<phid>[^/]+)/'
          => 'PhorgeSubscriptionsTransactionController',
      ),
    );
  }

}
