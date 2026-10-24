<?php

/**
 * Identify the "sudo" implementation on this host and format commands for it.
 *
 * sudo-rs, the default on some newer distros, ignores the "-E" flag with a
 * warning and runs the command with a reset environment. If we detect that
 * it is running, we pass a "--preserve-env=..." list instead. Classic sudo
 * writes the value of every listed variable to syslog, so we need to check
 * which version of the binary is running rather than defaulting to
 * `--preserve-env` across the board.
 */
final class PhabricatorSudoBinary extends Phobject {

  const FLAVOR_CLASSIC = 'sudo';
  const FLAVOR_RUST = 'sudo-rs';

  // The first sudo-rs release that supports "--preserve-env=list".
  const MINIMUM_RUST_VERSION = '0.2.7';

  private $path;
  private $flavor;
  private $version;

  public static function newFromVersionOutput($path, $output) {
    $binary = new self();
    $binary->path = $path;

    $matches = null;
    if (preg_match('/^sudo-rs (\d+\.\d+\.\d+)/m', $output, $matches)) {
      $binary->flavor = self::FLAVOR_RUST;
      $binary->version = $matches[1];
    } else {
      $binary->flavor = self::FLAVOR_CLASSIC;
    }

    return $binary;
  }

  public static function loadForPath($path) {
    static $binaries = array();

    // Key on the resolved file so that switching "sudo" between
    // implementations with "update-alternatives" gives a new key.
    $real_path = realpath($path);
    if ($real_path === false) {
      $real_path = $path;
    }
    $cache_key = 'sudo.binary('.sha1(implode(':', array(
      $real_path,
      @filemtime($real_path),
      @filesize($real_path),
    ))).')';

    if (isset($binaries[$cache_key])) {
      return $binaries[$cache_key];
    }

    $cache = PhabricatorCaches::getRuntimeCache();
    $output = $cache->getKey($cache_key);
    if ($output === null) {
      // sudo-rs prints the version to stderr. If "-V" fails, the output
      // has no sudo-rs version line and the binary is handled as classic
      // sudo
      list($err, $stdout, $stderr) = exec_manual('%s -V', $path);
      $output = $stdout."\n".$stderr;
      if (!$err) {
        $cache->setKey($cache_key, $output);
      }
    }

    $binary = self::newFromVersionOutput($path, $output);
    $binaries[$cache_key] = $binary;

    return $binary;
  }

  public function getPath() {
    return $this->path;
  }

  public function getFlavor() {
    return $this->flavor;
  }

  public function getVersion() {
    return $this->version;
  }

  public function isUsable() {
    return ($this->getUnusableReason() === null);
  }

  public function getUnusableReason() {
    if ($this->flavor !== self::FLAVOR_RUST) {
      return null;
    }

    if (version_compare($this->version, self::MINIMUM_RUST_VERSION, '>=')) {
      return null;
    }

    $platform = PlatformSymbols::getPlatformServerName();

    return pht(
      'The "sudo" binary at "%s" is sudo-rs %s. %s needs sudo-rs %s or '.
      'newer, because older releases do not support "--preserve-env=list" '.
      'and run commands without the environment %s sets. Upgrade sudo-rs or '.
      'install classic sudo.',
      $this->path,
      $this->version,
      $platform,
      self::MINIMUM_RUST_VERSION,
      $platform);
  }

  /**
   * @param string $user User to run the command as.
   * @param PhutilCommandString $command Command to run.
   * @param list<string> $env_keys Variables the command needs. Only sudo-rs
   *   uses this list; classic sudo preserves the whole environment.
   * @return PhutilCommandString
   */
  public function formatCommand($user, $command, array $env_keys) {
    $reason = $this->getUnusableReason();
    if ($reason !== null) {
      throw new Exception($reason);
    }

    // Flags here are:
    //
    //   -E: Preserve the environment (classic sudo only).
    //   -n: Non-interactive. Exit with an error instead of prompting.
    //   -u: Which user to sudo to.
    //   --preserve-env=...: Preserve these variables (sudo-rs only).

    if ($this->flavor === self::FLAVOR_CLASSIC) {
      return csprintf('%s -E -n -u %s -- %C', $this->path, $user, $command);
    }

    if (!$env_keys) {
      return csprintf('%s -n -u %s -- %C', $this->path, $user, $command);
    }

    return csprintf(
      '%s -n -u %s --preserve-env=%s -- %C',
      $this->path,
      $user,
      implode(',', $env_keys),
      $command);
  }

}
