<?php

final class PhorgePolicyApplication extends PhorgeApplication {

  public function getName() {
    return pht('Policy');
  }

  public function isLaunchable() {
    return false;
  }

  public function canUninstall() {
    return false;
  }

  public function getRoutes() {
    return array(
      '/policy/' => array(
        'explain/(?P<phid>[^/]+)/(?P<capability>[^/]+)/'
          => 'PhorgePolicyExplainController',
        'edit/'.
          '(?:'.
            'object/(?P<objectPHID>[^/]+)'.
            '|'.
            'type/(?P<objectType>[^/]+)'.
            '|'.
            'template/(?P<templateType>[^/]+)'.
          ')/'.
          '(?:(?P<phid>[^/]+)/)?' => 'PhorgePolicyEditController',
      ),
    );
  }

}
