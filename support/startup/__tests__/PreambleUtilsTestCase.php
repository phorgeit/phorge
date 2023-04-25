#!/usr/bin/env php
<?php

/**
 * /startup/ is not a Phutil library, so it can't use the phutil test fixture.
 * This script will just run the tests directly.
 *
 * NOTE: This test file will not run as part of `arc unit` run!
 */


final class PreambleUtilsTestCase {


  public function testTrustXForwardValues() {
    // For specific values of `$_SERVER['HTTP_X_FORWARDED_FOR']`,
    // `$_SERVER['REMOTE_ADDR']` will be updated with the result.


    $undefined = 'SPECIAL::UNDEFINED';
    $null_value = 'SPECIAL::NULL';

    $test_cases = array(
      'abc' => 'abc',
      $null_value => $undefined,
      '' => $undefined,

      // Strange, unexpected cases:
      144 => '144',
    );

    foreach ($test_cases as $input => $expected) {
      switch ($input) {
        case $undefined:
          unset($_SERVER['HTTP_X_FORWARDED_FOR']);
          break;

        case $null_value:
          $_SERVER['HTTP_X_FORWARDED_FOR'] = null;
          break;

        default:
          $_SERVER['HTTP_X_FORWARDED_FOR'] = $input;
          break;
      }

      unset($_SERVER['REMOTE_ADDR']);

      preamble_trust_x_forwarded_for_header();

      if (!isset($_SERVER['REMOTE_ADDR'])) {
        if ($expected === $undefined) {
          // test pass
          continue;
        } else {
          $this->failTest("Failed for input {$input} - result is not defined!");
        }
      }

      $actual = $_SERVER['REMOTE_ADDR'];

      if ($actual !== $expected) {
        var_dump($actual);

        $this->failTest(
          "Failed for input {$input} - actual output is {$actual}");
      }

    }

  }


  private function failTest($message = null) {
    echo $message;
    echo "\n";
    throw new Exception();
  }

  /**
   * Run all tests in this class.
   *
   * Return: True if all tests passed; False if any test failed.
   */
  final public function run() {
    $reflection = new ReflectionClass($this);
    $methods = $reflection->getMethods();

    $any_fail = false;

    // Try to ensure that poorly-written tests which depend on execution order
    // (and are thus not properly isolated) will fail.
    shuffle($methods);

    foreach ($methods as $method) {
      $name = $method->getName();
      if (!preg_match('/^test/', $name)) {
        continue;
      }

      try {
        call_user_func_array(
          array($this, $name),
          array());
        echo "Test passed: {$name}\n";
      } catch (Throwable $ex) {
        $any_fail = true;
        echo "Failed test: {$name}\n";
      }
    }
    return !$any_fail;
  }

}

require_once dirname(__DIR__).'/preamble-utils.php';

$test_case = new PreambleUtilsTestCase();
$good = $test_case->run();

if (!$good) {
  exit(3);
}
