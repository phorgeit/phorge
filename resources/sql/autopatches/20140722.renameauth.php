<?php

$map = array(
  'PhorgeAuthProviderOAuthAmazon' => 'PhorgeAmazonAuthProvider',
  'PhorgeAuthProviderOAuthAsana' => 'PhorgeAsanaAuthProvider',
  'PhorgeAuthProviderOAuth1Bitbucket'
    => 'PhorgeBitbucketAuthProvider',
  'PhorgeAuthProviderOAuthDisqus' => 'PhorgeDisqusAuthProvider',
  'PhorgeAuthProviderOAuthFacebook' => 'PhorgeFacebookAuthProvider',
  'PhorgeAuthProviderOAuthGitHub' => 'PhorgeGitHubAuthProvider',
  'PhorgeAuthProviderOAuthGoogle' => 'PhorgeGoogleAuthProvider',
  'PhorgeAuthProviderOAuth1JIRA' => 'PhorgeJIRAAuthProvider',
  'PhorgeAuthProviderLDAP' => 'PhorgeLDAPAuthProvider',
  'PhorgeAuthProviderPassword' => 'PhorgePasswordAuthProvider',
  'PhorgeAuthProviderPersona' => 'PhorgePersonaAuthProvider',
  'PhorgeAuthProviderOAuthTwitch' => 'PhorgeTwitchAuthProvider',
  'PhorgeAuthProviderOAuth1Twitter' => 'PhorgeTwitterAuthProvider',
  'PhorgeAuthProviderOAuthWordPress' => 'PhorgeWordPressAuthProvider',
);

echo pht('Migrating auth providers...')."\n";
$table = new PhorgeAuthProviderConfig();
$conn_w = $table->establishConnection('w');

foreach (new LiskMigrationIterator($table) as $provider) {
  $provider_class = $provider->getProviderClass();

  queryfx(
    $conn_w,
    'UPDATE %T SET providerClass = %s WHERE id = %d',
    $provider->getTableName(),
    idx($map, $provider_class, $provider_class),
    $provider->getID());
}
