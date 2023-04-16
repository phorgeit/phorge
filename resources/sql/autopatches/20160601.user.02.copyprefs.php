<?php

// Move timezone, translation and pronoun from the user object to preferences
// so they can be defaulted and edited like other settings.

$table = new PhorgeUser();
$conn_w = $table->establishConnection('w');
$table_name = $table->getTableName();
$prefs_table = new PhorgeUserPreferences();

foreach (new LiskRawMigrationIterator($conn_w, $table_name) as $row) {
  $phid = $row['phid'];

  $pref_row = queryfx_one(
    $conn_w,
    'SELECT preferences FROM %T WHERE userPHID = %s',
    $prefs_table->getTableName(),
    $phid);

  if ($pref_row) {
    try {
      $prefs = phutil_json_decode($pref_row['preferences']);
    } catch (Exception $ex) {
      $prefs = array();
    }
  } else {
    $prefs = array();
  }

  $zone = $row['timezoneIdentifier'];
  if (strlen($zone)) {
    $prefs[PhorgeTimezoneSetting::SETTINGKEY] = $zone;
  }

  $pronoun = $row['sex'];
  if (strlen($pronoun)) {
    $prefs[PhorgePronounSetting::SETTINGKEY] = $pronoun;
  }

  $translation = $row['translation'];
  if (strlen($translation)) {
    $prefs[PhorgeTranslationSetting::SETTINGKEY] = $translation;
  }

  if ($prefs) {
    queryfx(
      $conn_w,
      'INSERT INTO %T (phid, userPHID, preferences, dateModified, dateCreated)
        VALUES (%s, %s, %s, UNIX_TIMESTAMP(), UNIX_TIMESTAMP())
        ON DUPLICATE KEY UPDATE preferences = VALUES(preferences)',
      $prefs_table->getTableName(),
      $prefs_table->generatePHID(),
      $phid,
      phutil_json_encode($prefs));
  }
}

$prefs_key = PhorgeUserPreferencesCacheType::KEY_PREFERENCES;
PhorgeUserCache::clearCacheForAllUsers($prefs_key);
