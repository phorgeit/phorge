<?php

abstract class PhorgeSetupCheck extends Phobject {

  private $issues;

  abstract protected function executeChecks();

  const GROUP_OTHER       = 'other';
  const GROUP_MYSQL       = 'mysql';
  const GROUP_PHP         = 'php';
  const GROUP_IMPORTANT   = 'important';

  public function getExecutionOrder() {
    if ($this->isPreflightCheck()) {
      return 0;
    } else {
      return 1000;
    }
  }

  /**
   * Should this check execute before we load configuration?
   *
   * The majority of checks (particularly, those checks which examine
   * configuration) should run in the normal setup phase, after configuration
   * loads. However, a small set of critical checks (mostly, tests for PHP
   * setup and extensions) need to run before we can load configuration.
   *
   * @return bool True to execute before configuration is loaded.
   */
  public function isPreflightCheck() {
    return false;
  }

  final protected function newIssue($key) {
    $issue = id(new PhorgeSetupIssue())
      ->setIssueKey($key);
    $this->issues[$key] = $issue;

    if ($this->getDefaultGroup()) {
      $issue->setGroup($this->getDefaultGroup());
    }

    return $issue;
  }

  final public function getIssues() {
    return $this->issues;
  }

  protected function addIssue(PhorgeSetupIssue $issue) {
    $this->issues[$issue->getIssueKey()] = $issue;
    return $this;
  }

  public function getDefaultGroup() {
    return null;
  }

  final public function runSetupChecks() {
    $this->issues = array();
    $this->executeChecks();
  }

  final public static function getOpenSetupIssueKeys() {
    $cache = PhorgeCaches::getSetupCache();
    return $cache->getKey('phorge.setup.issue-keys');
  }

  final public static function resetSetupState() {
    $cache = PhorgeCaches::getSetupCache();
    $cache->deleteKey('phorge.setup.issue-keys');

    $server_cache = PhorgeCaches::getServerStateCache();
    $server_cache->deleteKey('phorge.in-flight');

    $use_scope = AphrontWriteGuard::isGuardActive();
    if ($use_scope) {
      $unguarded = AphrontWriteGuard::beginScopedUnguardedWrites();
    } else {
      AphrontWriteGuard::allowDangerousUnguardedWrites(true);
    }

    try {
      $db_cache = new PhorgeKeyValueDatabaseCache();
      $db_cache->deleteKey('phorge.setup.issue-keys');
    } catch (Exception $ex) {
      // If we hit an exception here, just ignore it. In particular, this can
      // happen on initial startup before the databases are initialized.
    }

    if ($use_scope) {
      unset($unguarded);
    } else {
      AphrontWriteGuard::allowDangerousUnguardedWrites(false);
    }
  }

  final public static function setOpenSetupIssueKeys(
    array $keys,
    $update_database) {
    $cache = PhorgeCaches::getSetupCache();
    $cache->setKey('phorge.setup.issue-keys', $keys);

    $server_cache = PhorgeCaches::getServerStateCache();
    $server_cache->setKey('phorge.in-flight', 1);

    if ($update_database) {
      $db_cache = new PhorgeKeyValueDatabaseCache();
      try {
        $json = phutil_json_encode($keys);
        $db_cache->setKey('phorge.setup.issue-keys', $json);
      } catch (Exception $ex) {
        // Ignore any write failures, since they likely just indicate that we
        // have a database-related setup issue that needs to be resolved.
      }
    }
  }

  final public static function getOpenSetupIssueKeysFromDatabase() {
    $db_cache = new PhorgeKeyValueDatabaseCache();
    try {
      $value = $db_cache->getKey('phorge.setup.issue-keys');
      if (!strlen($value)) {
        return null;
      }
      return phutil_json_decode($value);
    } catch (Exception $ex) {
      return null;
    }
  }

  final public static function getUnignoredIssueKeys(array $all_issues) {
    assert_instances_of($all_issues, 'PhorgeSetupIssue');
    $keys = array();
    foreach ($all_issues as $issue) {
      if (!$issue->getIsIgnored()) {
        $keys[] = $issue->getIssueKey();
      }
    }
    return $keys;
  }

  final public static function getConfigNeedsRepair() {
    $cache = PhorgeCaches::getSetupCache();
    return $cache->getKey('phorge.setup.needs-repair');
  }

  final public static function setConfigNeedsRepair($needs_repair) {
    $cache = PhorgeCaches::getSetupCache();
    $cache->setKey('phorge.setup.needs-repair', $needs_repair);
  }

  final public static function deleteSetupCheckCache() {
    $cache = PhorgeCaches::getSetupCache();
    $cache->deleteKeys(
      array(
        'phorge.setup.needs-repair',
        'phorge.setup.issue-keys',
      ));
  }

  final public static function willPreflightRequest() {
    $checks = self::loadAllChecks();

    foreach ($checks as $check) {
      if (!$check->isPreflightCheck()) {
        continue;
      }

      $check->runSetupChecks();

      foreach ($check->getIssues() as $key => $issue) {
        return self::newIssueResponse($issue);
      }
    }

    return null;
  }

  public static function newIssueResponse(PhorgeSetupIssue $issue) {
    $view = id(new PhorgeSetupIssueView())
      ->setIssue($issue);

    return id(new PhorgeConfigResponse())
      ->setView($view);
  }

  final public static function willProcessRequest() {
    $issue_keys = self::getOpenSetupIssueKeys();
    if ($issue_keys === null) {
      $engine = new PhorgeSetupEngine();
      $response = $engine->execute();
      if ($response) {
        return $response;
      }
    } else if ($issue_keys) {
      // If Phorge is configured in a cluster with multiple web devices,
      // we can end up with setup issues cached on every device. This can cause
      // a warning banner to show on every device so that each one needs to
      // be dismissed individually, which is pretty annoying. See T10876.

      // To avoid this, check if the issues we found have already been cleared
      // in the database. If they have, we'll just wipe out our own cache and
      // move on.
      $issue_keys = self::getOpenSetupIssueKeysFromDatabase();
      if ($issue_keys !== null) {
        self::setOpenSetupIssueKeys($issue_keys, $update_database = false);
      }
    }

    // Try to repair configuration unless we have a clean bill of health on it.
    // We need to keep doing this on every page load until all the problems
    // are fixed, which is why it's separate from setup checks (which run
    // once per restart).
    $needs_repair = self::getConfigNeedsRepair();
    if ($needs_repair !== false) {
      $needs_repair = self::repairConfig();
      self::setConfigNeedsRepair($needs_repair);
    }
  }

  /**
   * Test if we've survived through setup on at least one normal request
   * without fataling.
   *
   * If we've made it through setup without hitting any fatals, we switch
   * to render a more friendly error page when encountering issues like
   * database connection failures. This gives users a smoother experience in
   * the face of intermittent failures.
   *
   * @return bool True if we've made it through setup since the last restart.
   */
  final public static function isInFlight() {
    $cache = PhorgeCaches::getServerStateCache();
    return (bool)$cache->getKey('phorge.in-flight');
  }

  final public static function loadAllChecks() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setSortMethod('getExecutionOrder')
      ->execute();
  }

  final public static function runNormalChecks() {
    $checks = self::loadAllChecks();

    foreach ($checks as $key => $check) {
      if ($check->isPreflightCheck()) {
        unset($checks[$key]);
      }
    }

    $issues = array();
    foreach ($checks as $check) {
      $check->runSetupChecks();
      foreach ($check->getIssues() as $key => $issue) {
        if (isset($issues[$key])) {
          throw new Exception(
            pht(
              "Two setup checks raised an issue with key '%s'!",
              $key));
        }
        $issues[$key] = $issue;
        if ($issue->getIsFatal()) {
          break 2;
        }
      }
    }

    $ignore_issues = PhorgeEnv::getEnvConfig('config.ignore-issues');
    foreach ($ignore_issues as $ignorable => $derp) {
      if (isset($issues[$ignorable])) {
        $issues[$ignorable]->setIsIgnored(true);
      }
    }

    return $issues;
  }

  final public static function repairConfig() {
    $needs_repair = false;

    $options = PhorgeApplicationConfigOptions::loadAllOptions();
    foreach ($options as $option) {
      try {
        $option->getGroup()->validateOption(
          $option,
          PhorgeEnv::getEnvConfig($option->getKey()));
      } catch (PhorgeConfigValidationException $ex) {
        PhorgeEnv::repairConfig($option->getKey(), $option->getDefault());
        $needs_repair = true;
      }
    }

    return $needs_repair;
  }

}
