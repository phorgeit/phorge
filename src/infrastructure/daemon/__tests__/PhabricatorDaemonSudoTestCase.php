<?php

final class PhabricatorDaemonSudoTestCase extends PhabricatorTestCase {

  public function testSudoEnvironmentKeys() {
    $env = array(
      'PHABRICATOR_USER' => 'alice',
      'HOME' => '/srv/phorge/support/empty/',
      'PATH' => '/usr/bin',
      'BASH_FUNC_x%%' => '() { :; }',
      'A,B' => 'comma',
    );

    $this->assertEqual(
      array('HOME', 'PHABRICATOR_USER'),
      PhabricatorDaemon::getSudoEnvironmentKeys($env, true));

    // Without "setEnv()", a future passes the process environment, which it
    // reads from $_ENV.
    $process_env = $_ENV;
    $_ENV = array(
      'HOME' => '/root',
      'LANG' => 'C.UTF-8',
      'LOGNAME' => 'root',
      'SUDO_USER' => 'admin',
      'USER' => 'root',
    ) + $_ENV;

    try {
      $keys = PhabricatorDaemon::getSudoEnvironmentKeys(
        array('PHABRICATOR_ENV' => 'test'));
      $this->assertTrue(in_array('LANG', $keys));
      $this->assertTrue(in_array('PHABRICATOR_ENV', $keys));
      $this->assertFalse(in_array('LOGNAME', $keys));
      $this->assertFalse(in_array('SUDO_USER', $keys));
      $this->assertFalse(in_array('USER', $keys));

      // Classic "-E" keeps the caller's HOME, so it is listed.
      $this->assertTrue(in_array('HOME', $keys));

      // A name the caller sets is kept.
      $keys = PhabricatorDaemon::getSudoEnvironmentKeys(
        array('USER' => 'alice'));
      $this->assertTrue(in_array('USER', $keys));
      $this->assertTrue(in_array('LANG', $keys));
      $this->assertFalse(in_array('LOGNAME', $keys));
    } finally {
      $_ENV = $process_env;
    }
  }

}
