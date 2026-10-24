<?php

final class PhabricatorSudoBinaryTestCase extends PhabricatorTestCase {

  public function testVersionParsing() {
    $tests = array(
      "Sudo version 1.9.15p5\nSudoers policy plugin version 1.9.15p5\n" =>
        array(PhabricatorSudoBinary::FLAVOR_CLASSIC, null, true),
      "Sudo version 1.9.17p2\n" =>
        array(PhabricatorSudoBinary::FLAVOR_CLASSIC, null, true),
      "sudo-rs 0.2.13-0ubuntu1.2\n" =>
        array(PhabricatorSudoBinary::FLAVOR_RUST, '0.2.13', true),
      "sudo-rs 0.2.8\n" =>
        array(PhabricatorSudoBinary::FLAVOR_RUST, '0.2.8', true),
      // Empty stdout joined with the stderr line, as sudo-rs 0.2.8 prints.
      "\nsudo-rs 0.2.8\n" =>
        array(PhabricatorSudoBinary::FLAVOR_RUST, '0.2.8', true),
      "sudo-rs 0.2.7\n" =>
        array(PhabricatorSudoBinary::FLAVOR_RUST, '0.2.7', true),
      "sudo-rs 0.2.6\n" =>
        array(PhabricatorSudoBinary::FLAVOR_RUST, '0.2.6', false),
      "sudo-rs 0.2.5\n" =>
        array(PhabricatorSudoBinary::FLAVOR_RUST, '0.2.5', false),
      "sudo-rs 0.10.0\n" =>
        array(PhabricatorSudoBinary::FLAVOR_RUST, '0.10.0', true),
      'unexpected output' =>
        array(PhabricatorSudoBinary::FLAVOR_CLASSIC, null, true),
    );

    foreach ($tests as $output => $expect) {
      list($flavor, $version, $usable) = $expect;
      $binary = PhabricatorSudoBinary::newFromVersionOutput(
        '/usr/bin/sudo',
        $output);

      $this->assertEqual($flavor, $binary->getFlavor(), $output);
      $this->assertEqual($version, $binary->getVersion(), $output);
      $this->assertEqual($usable, $binary->isUsable(), $output);
      $this->assertEqual('/usr/bin/sudo', $binary->getPath(), $output);
    }
  }

  public function testFormatCommand() {
    $command = csprintf('git %s', 'fetch');
    $keys = array('HOME', 'PHABRICATOR_ENV');

    $classic = PhabricatorSudoBinary::newFromVersionOutput(
      '/usr/bin/sudo',
      'Sudo version 1.9.15p5');
    $this->assertEqual(
      "/usr/bin/sudo -E -n -u 'daemon user' -- git fetch",
      (string)$classic->formatCommand('daemon user', $command, $keys));

    $rust = PhabricatorSudoBinary::newFromVersionOutput(
      '/usr/bin/sudo',
      'sudo-rs 0.2.13');
    $this->assertEqual(
      "/usr/bin/sudo -n -u 'daemon user' ".
      "--preserve-env='HOME,PHABRICATOR_ENV' -- git fetch",
      (string)$rust->formatCommand('daemon user', $command, $keys));
    $this->assertEqual(
      "/usr/bin/sudo -n -u 'daemon user' -- git fetch",
      (string)$rust->formatCommand('daemon user', $command, array()));

    $old = PhabricatorSudoBinary::newFromVersionOutput(
      '/usr/bin/sudo-rs',
      'sudo-rs 0.2.5');
    $caught = null;
    try {
      $old->formatCommand('daemon', $command, $keys);
    } catch (Throwable $ex) {
      $caught = $ex;
    }
    $this->assertTrue($caught instanceof Exception);
  }

  public function testLoadForPath() {
    $tests = array(
      // sudo-rs 0.2.13 prints its version on stdout.
      "echo 'sudo-rs 0.2.13'" => PhabricatorSudoBinary::FLAVOR_RUST,
      // sudo-rs 0.2.5 and 0.2.8 print it on stderr.
      "echo 'sudo-rs 0.2.8' >&2" => PhabricatorSudoBinary::FLAVOR_RUST,
      // A binary that rejects "-V" keeps the classic "-E" form.
      "echo 'unknown option' >&2; exit 1" =>
        PhabricatorSudoBinary::FLAVOR_CLASSIC,
    );

    foreach ($tests as $script => $flavor) {
      $tmp = new TempFile();
      Filesystem::writeFile($tmp, "#!/bin/sh\n{$script}\n");
      Filesystem::changePermissions($tmp, 0755);

      list($err) = exec_manual('%s', $tmp);
      if ($err == 126) {
        $this->assertSkipped(pht('The temporary directory is not executable.'));
      }

      $binary = PhabricatorSudoBinary::loadForPath((string)$tmp);
      $this->assertEqual($flavor, $binary->getFlavor(), $script);

      // A second load returns the same object from the in-process cache.
      $this->assertTrue(
        $binary === PhabricatorSudoBinary::loadForPath((string)$tmp));
    }
  }

}
