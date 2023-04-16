<?php

final class PhorgeEditEngineSubtypeTestCase
  extends PhorgeTestCase {

  public function testEditEngineSubtypeKeys() {
    $map = array(
      // Too short.
      'a' => false,
      'ab' => false,

      // Too long.
      'mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm'.
      'mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm' => false,

      // Junk.
      '!_(#(31 1~' => false,

      // These are reasonable and valid.
      'default' => true,
      'bug' => true,
      'feature' => true,
      'risk' => true,
      'security' => true,
    );

    foreach ($map as $input => $expect) {
      try {
        PhorgeEditEngineSubtype::validateSubtypeKey($input);
        $actual = true;
      } catch (Exception $ex) {
        $actual = false;
      }

      $this->assertEqual($expect, $actual, pht('Subtype Key "%s"', $input));
    }
  }
}
