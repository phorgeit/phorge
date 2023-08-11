<?php

final class DifferentialCommitMessageFieldTestCase
  extends PhabricatorTestCase {

  public function testRevisionCommitMessageFieldParsing() {
    $base_uri = 'https://www.example.com/';

    $tests = array(
      'D123' => 123,
      'd123' => 123,
      "  \n  d123 \n " => 123,
      "D123\nSome-Custom-Field: The End" => 123,
      "{$base_uri}D123" => 123,
      "{$base_uri}D123\nSome-Custom-Field: The End" => 123,
      'https://www.other.com/D123' => null,
    );

    $env = PhabricatorEnv::beginScopedEnv();
    $env->overrideEnvConfig('phabricator.base-uri', $base_uri);

    foreach ($tests as $input => $expect) {
      $actual = id(new DifferentialRevisionIDCommitMessageField())
        ->parseFieldValue($input);
      $this->assertEqual($expect, $actual, pht('Parse of: %s', $input));
    }

    unset($env);
  }

  public function testRenderFieldValue() {
    $test_object = new DifferentialRevertPlanCommitMessageField();
    $this->assertEqual('foo', $test_object->renderFieldValue('foo'),
      'Normal strings should be rendered unaltered');

    $this->assertEqual(null, $test_object->renderFieldValue(''),
      'Empty strings should be returned as null');

    $this->assertEqual(null, $test_object->renderFieldValue(null),
      'null values strings should be returned as null');

    $test_object = new DifferentialRevisionIDCommitMessageField();
    $expected = 'http://phabricator.example.com/D123';
    $this->assertEqual($expected, $test_object->renderFieldValue('123'));
    $this->assertEqual(null, $test_object->renderFieldValue(null));
  }

}
