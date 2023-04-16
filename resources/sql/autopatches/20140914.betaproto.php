<?php

$old_key = 'phorge.show-beta-applications';
$new_key = 'phorge.show-prototypes';

echo pht("Migrating '%s' to '%s'...", $old_key, $new_key)."\n";

if (PhorgeEnv::getEnvConfig($new_key)) {
  echo pht('Skipping migration, new data is already set.')."\n";
  return;
}

$old = PhorgeEnv::getEnvConfigIfExists($old_key);
if (!$old) {
  echo pht('Skipping migration, old data does not exist.')."\n";
  return;
}

PhorgeConfigEntry::loadConfigEntry($new_key)
  ->setIsDeleted(0)
  ->setValue($old)
  ->save();

echo pht('Done.')."\n";
