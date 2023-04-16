<?php

$key = 'metamta.maniphest.public-create-email';
echo pht("Migrating `%s` to new application email infrastructure...\n", $key);
$value = PhorgeEnv::getEnvConfigIfExists($key);
$maniphest = new PhorgeManiphestApplication();

if ($value) {
  try {
    PhorgeMetaMTAApplicationEmail::initializeNewAppEmail(
      PhorgeUser::getOmnipotentUser())
      ->setAddress($value)
      ->setApplicationPHID($maniphest->getPHID())
      ->save();
  } catch (AphrontDuplicateKeyQueryException $ex) {
    // Already migrated?
  }
}

echo pht('Done.')."\n";
