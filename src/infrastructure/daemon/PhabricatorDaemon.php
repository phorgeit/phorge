<?php

abstract class PhabricatorDaemon extends PhutilDaemon {

  protected function willRun() {
    parent::willRun();

    $phabricator = phutil_get_library_root('phabricator');
    $root = dirname($phabricator);
    require_once $root.'/scripts/__init_script__.php';
  }

  protected function willSleep($duration) {
    LiskDAO::closeInactiveConnections(60);
    return;
  }

  public function getViewer() {
    return PhabricatorUser::getOmnipotentUser();
  }


  /**
   * Format a command so it executes as the daemon user, if a daemon user is
   * defined. This wraps the provided command in `sudo -u ...`, roughly.
   *
   * Pass the same environment the caller gives to the future's `setEnv()`.
   * sudo-rs drops any variable that is not explicitly passed to
   * `--preserve-env`.
   *
   * @param   PhutilCommandString $command Command to execute.
   * @param   map<string, string> $env (optional) Environment the future
   *   will set.
   * @param   bool $wipe_process_env (optional) True if the future will
   *   replace the process environment with `$env`.
   * @return  PhutilCommandString `sudo` version of the command.
   */
  public static function sudoCommandAsDaemonUser(
    $command,
    array $env = array(),
    $wipe_process_env = false) {

    $user = PhabricatorEnv::getEnvConfig('phd.user');
    if (!$user) {
      // No daemon user is set, so just run this as ourselves.
      return $command;
    }

    // We may reach this method while already running as the daemon user: for
    // example, active and passive synchronization of clustered repositories
    // run the same commands through the same code, but as different users.

    // By default, `sudo` won't let you sudo to yourself, so we can get into
    // trouble if we're already running as the daemon user unless the host has
    // been configured to let the daemon user run commands as itself.

    // Since this is silly and more complicated than doing this check, don't
    // use `sudo` if we're already running as the correct user.
    if (self::isRunningAsUser($user)) {
      return $command;
    }

    return self::sudoCommandAsUser($user, $command, $env, $wipe_process_env);
  }

  /**
   * Format a command so it executes as a given user through `sudo`.
   *
   * @param   string $user User to run the command as.
   * @param   PhutilCommandString $command Command to execute.
   * @param   map<string, string> $env (optional) Environment the future
   *   will set.
   * @param   bool $wipe_process_env (optional) True if the future will
   *   replace the process environment with `$env`.
   * @return  PhutilCommandString `sudo` version of the command.
   */
  public static function sudoCommandAsUser(
    $user,
    $command,
    array $env = array(),
    $wipe_process_env = false) {

    // Get the absolute path so we're safe against the caller wiping out
    // PATH.
    $sudo = Filesystem::resolveBinary('sudo');
    if (!$sudo) {
      throw new Exception(pht("Unable to find 'sudo'!"));
    }

    $env_keys = self::getSudoEnvironmentKeys($env, $wipe_process_env);

    return PhabricatorSudoBinary::loadForPath($sudo)
      ->formatCommand($user, $command, $env_keys);
  }

  public static function isRunningAsUser($user) {
    if (!function_exists('posix_getuid')) {
      return false;
    }

    $info = posix_getpwuid(posix_getuid());
    return ($info && $info['name'] == $user);
  }

  /**
   * List the variables a future will pass to `sudo`, in the form sudo-rs
   * accepts in `--preserve-env`.
   *
   * @param map<string, string> $env Environment the future will set.
   * @param bool $wipe_process_env (optional) True if the future will
   *   replace the process environment with `$env`.
   * @return list<string> Sorted variable names.
   */
  public static function getSudoEnvironmentKeys(
    array $env,
    $wipe_process_env = false) {

    $future_env = id(new ExecFuture('true'))
      ->setEnv($env, $wipe_process_env)
      ->getEnv();

    // Classic "-E" keeps "secure_path" in place of the caller's PATH, and
    // listing PATH would override "secure_path" on sudo-rs.
    unset($future_env['PATH']);

    // sudo sets these for the target user, and a caller's "SUDO_*" values
    // would replace the ones sudo sets, so only list them if the caller
    // passes them explicitly. Classic "-E" also keeps the caller's MAIL and
    // SHELL, but nothing Phorge runs reads them.

    // HOME is not in this list: classic "-E" keeps the caller's HOME, and
    // sudo-rs only does the same if HOME is listed.
    $target_keys = array(
      'LOGNAME' => true,
      'MAIL' => true,
      'SHELL' => true,
      'USER' => true,
      'USERNAME' => true,
    );
    $keys = array();
    foreach ($future_env as $key => $value) {
      // Skip names that can not appear in a comma-separated list, such as
      // the "BASH_FUNC_name%%" entries bash exports.
      if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*\z/', $key)) {
        continue;
      }
      if (!array_key_exists($key, $env)) {
        if (isset($target_keys[$key]) || !strncmp($key, 'SUDO_', 5)) {
          continue;
        }
      }
      $keys[] = $key;
    }
    sort($keys);

    return $keys;
  }

}
