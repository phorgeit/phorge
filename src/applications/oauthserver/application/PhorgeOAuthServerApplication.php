<?php

final class PhorgeOAuthServerApplication extends PhorgeApplication {

  public function getName() {
    return pht('OAuth Server');
  }

  public function getBaseURI() {
    return '/oauthserver/';
  }

  public function getShortDescription() {
    return pht('OAuth Login Provider');
  }

  public function getIcon() {
    return 'fa-hotel';
  }

  public function getTitleGlyph() {
    return "\xE2\x99\x86";
  }

  public function getFlavorText() {
    return pht(
      'Log In with %s',
      PlatformSymbols::getPlatformServerName());
  }

  public function getApplicationGroup() {
    return self::GROUP_ADMIN;
  }

  public function isPrototype() {
    return true;
  }

  public function getHelpDocumentationArticles(PhorgeUser $viewer) {
    return array(
      array(
        'name' => pht('Using the Phorge OAuth Server'),
        'href' => PhorgeEnv::getDoclink(
          'Using the Phorge OAuth Server'),
      ),
    );
  }

  public function getRoutes() {
    return array(
      '/oauthserver/' => array(
        '(?:query/(?P<queryKey>[^/]+)/)?'
          => 'PhorgeOAuthClientListController',
        'auth/' => 'PhorgeOAuthServerAuthController',
        'token/' => 'PhorgeOAuthServerTokenController',
        $this->getEditRoutePattern('edit/') =>
          'PhorgeOAuthClientEditController',
          'client/' => array(
          'disable/(?P<id>\d+)/' => 'PhorgeOAuthClientDisableController',
          'view/(?P<id>\d+)/' => 'PhorgeOAuthClientViewController',
          'secret/(?P<id>\d+)/' => 'PhorgeOAuthClientSecretController',
          'test/(?P<id>\d+)/' => 'PhorgeOAuthClientTestController',
        ),
      ),
    );
  }

  protected function getCustomCapabilities() {
    return array(
      PhorgeOAuthServerCreateClientsCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
      ),
    );
  }

}
