<?php

final class PhorgeAuthApplication extends PhorgeApplication {

  public function canUninstall() {
    return false;
  }

  public function getBaseURI() {
    return '/auth/';
  }

  public function getIcon() {
    return 'fa-key';
  }

  public function isPinnedByDefault(PhorgeUser $viewer) {
    return $viewer->getIsAdmin();
  }

  public function getName() {
    return pht('Auth');
  }

  public function getShortDescription() {
    return pht('Login/Registration');
  }

  public function getHelpDocumentationArticles(PhorgeUser $viewer) {
    // NOTE: Although reasonable help exists for this in "Configuring Accounts
    // and Registration", specifying help items here means we get the menu
    // item in all the login/link interfaces, which is confusing and not
    // helpful.

    // TODO: Special case this, or split the auth and auth administration
    // applications?

    return array();
  }

  public function getApplicationGroup() {
    return self::GROUP_ADMIN;
  }

  public function getRoutes() {
    return array(
      '/auth/' => array(
        '' => 'PhorgeAuthListController',
        'config/' => array(
          'new/' => 'PhorgeAuthNewController',
          'edit/(?:(?P<id>\d+)/)?' => 'PhorgeAuthEditController',
          '(?P<action>enable|disable)/(?P<id>\d+)/'
            => 'PhorgeAuthDisableController',
          'view/(?P<id>\d+)/' => 'PhorgeAuthProviderViewController',
        ),
        'login/(?P<pkey>[^/]+)/(?:(?P<extra>[^/]+)/)?'
          => 'PhorgeAuthLoginController',
        '(?P<loggedout>loggedout)/' => 'PhorgeAuthStartController',
        'invite/(?P<code>[^/]+)/' => 'PhorgeAuthInviteController',
        'register/(?:(?P<akey>[^/]+)/)?' => 'PhorgeAuthRegisterController',
        'start/' => 'PhorgeAuthStartController',
        'validate/' => 'PhorgeAuthValidateController',
        'finish/' => 'PhorgeAuthFinishController',
        'unlink/(?P<id>\d+)/' => 'PhorgeAuthUnlinkController',
        '(?P<action>link|refresh)/(?P<id>\d+)/'
          => 'PhorgeAuthLinkController',
        'confirmlink/(?P<akey>[^/]+)/'
          => 'PhorgeAuthConfirmLinkController',
        'session/terminate/(?P<id>[^/]+)/'
          => 'PhorgeAuthTerminateSessionController',
        'token/revoke/(?P<id>[^/]+)/'
          => 'PhorgeAuthRevokeTokenController',
        'session/downgrade/'
          => 'PhorgeAuthDowngradeSessionController',
        'enroll/' => array(
          '(?:(?P<pageKey>[^/]+)/)?'
            => 'PhorgeAuthNeedsMultiFactorController',
        ),
        'sshkey/' => array(
          $this->getQueryRoutePattern('for/(?P<forPHID>[^/]+)/')
            => 'PhorgeAuthSSHKeyListController',
          'generate/' => 'PhorgeAuthSSHKeyGenerateController',
          'upload/' => 'PhorgeAuthSSHKeyEditController',
          'edit/(?P<id>\d+)/' => 'PhorgeAuthSSHKeyEditController',
          'revoke/(?P<id>\d+)/'
            => 'PhorgeAuthSSHKeyRevokeController',
          'view/(?P<id>\d+)/' => 'PhorgeAuthSSHKeyViewController',
        ),

        'password/' => 'PhorgeAuthSetPasswordController',
        'external/' => 'PhorgeAuthSetExternalController',

        'mfa/' => array(
          $this->getQueryRoutePattern() =>
            'PhorgeAuthFactorProviderListController',
          $this->getEditRoutePattern('edit/') =>
            'PhorgeAuthFactorProviderEditController',
          '(?P<id>[1-9]\d*)/' =>
            'PhorgeAuthFactorProviderViewController',
          'message/(?P<id>[1-9]\d*)/' =>
            'PhorgeAuthFactorProviderMessageController',
          'challenge/status/(?P<id>[1-9]\d*)/' =>
            'PhorgeAuthChallengeStatusController',
        ),

        'message/' => array(
          $this->getQueryRoutePattern() =>
            'PhorgeAuthMessageListController',
          $this->getEditRoutePattern('edit/') =>
            'PhorgeAuthMessageEditController',
          '(?P<id>[^/]+)/' =>
            'PhorgeAuthMessageViewController',
        ),

        'contact/' => array(
          $this->getEditRoutePattern('edit/') =>
            'PhorgeAuthContactNumberEditController',
          '(?P<id>[1-9]\d*)/' =>
            'PhorgeAuthContactNumberViewController',
          '(?P<action>disable|enable)/(?P<id>[1-9]\d*)/' =>
            'PhorgeAuthContactNumberDisableController',
          'primary/(?P<id>[1-9]\d*)/' =>
            'PhorgeAuthContactNumberPrimaryController',
          'test/(?P<id>[1-9]\d*)/' =>
            'PhorgeAuthContactNumberTestController',
        ),
      ),

      '/oauth/(?P<provider>\w+)/login/'
        => 'PhorgeAuthOldOAuthRedirectController',

      '/login/' => array(
        '' => 'PhorgeAuthStartController',
        'email/' => 'PhorgeEmailLoginController',
        'once/'.
          '(?P<type>[^/]+)/'.
          '(?P<id>\d+)/'.
          '(?P<key>[^/]+)/'.
          '(?:(?P<emailID>\d+)/)?' => 'PhorgeAuthOneTimeLoginController',
        'refresh/' => 'PhorgeRefreshCSRFController',
        'mustverify/' => 'PhorgeMustVerifyEmailController',
      ),

      '/emailverify/(?P<code>[^/]+)/'
        => 'PhorgeEmailVerificationController',

      '/logout/' => 'PhorgeLogoutController',
    );
  }

  protected function getCustomCapabilities() {
    return array(
      AuthManageProvidersCapability::CAPABILITY => array(
        'default' => PhorgePolicies::POLICY_ADMIN,
      ),
    );
  }
}
