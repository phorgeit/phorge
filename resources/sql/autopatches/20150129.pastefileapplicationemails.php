<?php

$key_files = 'metamta.files.public-create-email';
$key_paste = 'metamta.paste.public-create-email';
echo pht(
  "Migrating `%s` and `%s` to new application email infrastructure...\n",
  $key_files,
  $key_paste);

$value_files = PhorgeEnv::getEnvConfigIfExists($key_files);
$files_app = new PhorgeFilesApplication();

if ($value_files) {
  try {
    PhorgeMetaMTAApplicationEmail::initializeNewAppEmail(
      PhorgeUser::getOmnipotentUser())
      ->setAddress($value_files)
      ->setApplicationPHID($files_app->getPHID())
      ->save();
  } catch (AphrontDuplicateKeyQueryException $ex) {
    // Already migrated?
  }
}

$value_paste = PhorgeEnv::getEnvConfigIfExists($key_paste);
$paste_app = new PhorgePasteApplication();

if ($value_paste) {
  try {
    PhorgeMetaMTAApplicationEmail::initializeNewAppEmail(
      PhorgeUser::getOmnipotentUser())
      ->setAddress($value_paste)
      ->setApplicationPHID($paste_app->getPHID())
      ->save();
  } catch (AphrontDuplicateKeyQueryException $ex) {
    // Already migrated?
  }
}

echo pht('Done.')."\n";
