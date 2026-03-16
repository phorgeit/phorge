<?php

final class PhabricatorPolicyApplication extends PhabricatorApplication {

  public function getName() {
    return pht('Policy');
  }

  public function isLaunchable() {
    return true;
  }

  public function canUninstall() {
    return false;
  }

  public function getBaseURI() {
    return '/policy/';
  }

  public function getRoutes() {
    return array(
      '/policy/' => array(
        '' => PhorgePolicyHomeController::class,
        'explain/(?P<phid>[^/]+)/(?P<capability>[^/]+)/'
          => 'PhabricatorPolicyExplainController',
        'edit/'.
          '(?:'.
            'object/(?P<objectPHID>[^/]+)'.
            '|'.
            'type/(?P<objectType>[^/]+)'.
            '|'.
            'template/(?P<templateType>[^/]+)'.
          ')/'.
          '(?:(?P<phid>[^/]+)/)?' => 'PhabricatorPolicyEditController',
        'named/' => array(
          '(?:(?P<id>\d+)/)' => PhorgePolicyViewNamedPolicyController::class,
          $this->getQueryRoutePattern() =>
            PhorgePolicyNamedPolicyListController::class,
          $this->getEditRoutePattern('edit/') =>
            PhorgePolicyEditNamedPolicyController::class,
          ),
      ),
    );
  }

}
