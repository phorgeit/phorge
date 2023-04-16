<?php

$config_map = array(
  'PhorgeLDAPAuthProvider'           => array(
    'enabled' => 'ldap.auth-enabled',
    'registration' => true,
    'type' => 'ldap',
    'domain' => 'self',
  ),
  'PhorgeAuthProviderOAuthDisqus'    => array(
    'enabled' => 'disqus.auth-enabled',
    'registration' => 'disqus.registration-enabled',
    'permanent' => 'disqus.auth-permanent',
    'oauth.id' => 'disqus.application-id',
    'oauth.secret' => 'disqus.application-secret',
    'type' => 'disqus',
    'domain' => 'disqus.com',
  ),
  'PhorgeFacebookAuthProvider'  => array(
    'enabled' => 'facebook.auth-enabled',
    'registration' => 'facebook.registration-enabled',
    'permanent' => 'facebook.auth-permanent',
    'oauth.id' => 'facebook.application-id',
    'oauth.secret' => 'facebook.application-secret',
    'type' => 'facebook',
    'domain' => 'facebook.com',
  ),
  'PhorgeAuthProviderOAuthGitHub'    => array(
    'enabled' => 'github.auth-enabled',
    'registration' => 'github.registration-enabled',
    'permanent' => 'github.auth-permanent',
    'oauth.id' => 'github.application-id',
    'oauth.secret' => 'github.application-secret',
    'type' => 'github',
    'domain' => 'github.com',
  ),
  'PhorgeAuthProviderOAuthGoogle'    => array(
    'enabled' => 'google.auth-enabled',
    'registration' => 'google.registration-enabled',
    'permanent' => 'google.auth-permanent',
    'oauth.id' => 'google.application-id',
    'oauth.secret' => 'google.application-secret',
    'type' => 'google',
    'domain' => 'google.com',
  ),
  'PhorgePasswordAuthProvider'       => array(
    'enabled' => 'auth.password-auth-enabled',
    'enabled-default' => false,
    'registration' => false,
    'type' => 'password',
    'domain' => 'self',
  ),
);

foreach ($config_map as $provider_class => $spec) {
  $enabled_key = idx($spec, 'enabled');
  $enabled_default = idx($spec, 'enabled-default', false);
  $enabled = PhorgeEnv::getEnvConfigIfExists(
    $enabled_key,
    $enabled_default);

  if (!$enabled) {
    echo pht('Skipping %s (not enabled).', $provider_class)."\n";
    // This provider was not previously enabled, so we can skip migrating it.
    continue;
  } else {
    echo pht('Migrating %s...', $provider_class)."\n";
  }

  $registration_key = idx($spec, 'registration');
  if ($registration_key === true) {
    $registration = 1;
  } else if ($registration_key === false) {
    $registration = 0;
  } else {
    $registration = (int)PhorgeEnv::getEnvConfigIfExists(
      $registration_key,
      true);
  }

  $unlink_key = idx($spec, 'permanent');
  if (!$unlink_key) {
    $unlink = 1;
  } else {
    $unlink = (int)(!PhorgeEnv::getEnvConfigIfExists($unlink_key));
  }

  $config = id(new PhorgeAuthProviderConfig())
    ->setIsEnabled(1)
    ->setShouldAllowLogin(1)
    ->setShouldAllowRegistration($registration)
    ->setShouldAllowLink(1)
    ->setShouldAllowUnlink($unlink)
    ->setProviderType(idx($spec, 'type'))
    ->setProviderDomain(idx($spec, 'domain'))
    ->setProviderClass($provider_class);

  if (isset($spec['oauth.id'])) {
    $config->setProperty(
      PhorgeAuthProviderOAuth::PROPERTY_APP_ID,
      PhorgeEnv::getEnvConfigIfExists(idx($spec, 'oauth.id')));
    $config->setProperty(
      PhorgeAuthProviderOAuth::PROPERTY_APP_SECRET,
      PhorgeEnv::getEnvConfigIfExists(idx($spec, 'oauth.secret')));
  }

  switch ($provider_class) {
    case 'PhorgeFacebookAuthProvider':
      $config->setProperty(
        PhorgeFacebookAuthProvider::KEY_REQUIRE_SECURE,
        (int)PhorgeEnv::getEnvConfigIfExists(
          'facebook.require-https-auth'));
      break;
    case 'PhorgeLDAPAuthProvider':

      $ldap_map = array(
        PhorgeLDAPAuthProvider::KEY_HOSTNAME
          => 'ldap.hostname',
        PhorgeLDAPAuthProvider::KEY_PORT
          => 'ldap.port',
        PhorgeLDAPAuthProvider::KEY_DISTINGUISHED_NAME
          => 'ldap.base_dn',
        PhorgeLDAPAuthProvider::KEY_SEARCH_ATTRIBUTES
          => 'ldap.search_attribute',
        PhorgeLDAPAuthProvider::KEY_USERNAME_ATTRIBUTE
          => 'ldap.username-attribute',
        PhorgeLDAPAuthProvider::KEY_REALNAME_ATTRIBUTES
          => 'ldap.real_name_attributes',
        PhorgeLDAPAuthProvider::KEY_VERSION
          => 'ldap.version',
        PhorgeLDAPAuthProvider::KEY_REFERRALS
          => 'ldap.referrals',
        PhorgeLDAPAuthProvider::KEY_START_TLS
          => 'ldap.start-tls',
        PhorgeLDAPAuthProvider::KEY_ANONYMOUS_USERNAME
          => 'ldap.anonymous-user-name',
        PhorgeLDAPAuthProvider::KEY_ANONYMOUS_PASSWORD
          => 'ldap.anonymous-user-password',
        // Update the old "search first" setting to the newer but similar
        // "always search" setting.
        PhorgeLDAPAuthProvider::KEY_ALWAYS_SEARCH
          => 'ldap.search-first',
        PhorgeLDAPAuthProvider::KEY_ACTIVEDIRECTORY_DOMAIN
          => 'ldap.activedirectory_domain',
      );

      $defaults = array(
        'ldap.version' => 3,
        'ldap.port' => 389,
      );

      foreach ($ldap_map as $pkey => $ckey) {
        $default = idx($defaults, $ckey);
        $config->setProperty(
          $pkey,
          PhorgeEnv::getEnvConfigIfExists($ckey, $default));
      }
      break;
  }

  $config->save();
}

echo pht('Done.')."\n";
