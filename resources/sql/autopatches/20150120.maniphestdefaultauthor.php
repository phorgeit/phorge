<?php

$key = 'metamta.maniphest.default-public-author';
echo pht("Migrating `%s` to new application email infrastructure...\n", $key);
$value = PhorgeEnv::getEnvConfigIfExists($key);
$maniphest = new PhorgeManiphestApplication();
$config_key =
  PhorgeMetaMTAApplicationEmail::CONFIG_DEFAULT_AUTHOR;

if ($value) {
  $app_emails = id(new PhorgeMetaMTAApplicationEmailQuery())
    ->setViewer(PhorgeUser::getOmnipotentUser())
    ->withApplicationPHIDs(array($maniphest->getPHID()))
    ->execute();

  foreach ($app_emails as $app_email) {
    $app_email->setConfigValue($config_key, $value);
    $app_email->save();
  }
}

echo pht('Done.')."\n";
