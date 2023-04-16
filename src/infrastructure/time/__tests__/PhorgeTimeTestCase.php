<?php

final class PhorgeTimeTestCase extends PhorgeTestCase {

  public function testPhorgeTimeStack() {
    $t = 1370202281;
    $time = PhorgeTime::pushTime($t, 'UTC');

    $this->assertTrue(PhorgeTime::getNow() === $t);

    unset($time);

    $this->assertFalse(PhorgeTime::getNow() === $t);
  }

  public function testParseLocalTime() {
    $u = new PhorgeUser();
    $u->overrideTimezoneIdentifier('UTC');

    $v = new PhorgeUser();
    $v->overrideTimezoneIdentifier('America/Los_Angeles');

    $t = 1370202281; // 2013-06-02 12:44:41 -0700
    $time = PhorgeTime::pushTime($t, 'America/Los_Angeles');

    $this->assertEqual(
      $t,
      PhorgeTime::parseLocalTime('now', $u));
    $this->assertEqual(
      $t,
      PhorgeTime::parseLocalTime('now', $v));

    $this->assertEqual(
      $t,
      PhorgeTime::parseLocalTime('2013-06-02 12:44:41 -0700', $u));
    $this->assertEqual(
      $t,
      PhorgeTime::parseLocalTime('2013-06-02 12:44:41 -0700', $v));

    $this->assertEqual(
      $t,
      PhorgeTime::parseLocalTime('2013-06-02 12:44:41 PDT', $u));
    $this->assertEqual(
      $t,
      PhorgeTime::parseLocalTime('2013-06-02 12:44:41 PDT', $v));

    $this->assertEqual(
      $t,
      PhorgeTime::parseLocalTime('2013-06-02 19:44:41', $u));
    $this->assertEqual(
      $t,
      PhorgeTime::parseLocalTime('2013-06-02 12:44:41', $v));

    $this->assertEqual(
      $t + 3600,
      PhorgeTime::parseLocalTime('+1 hour', $u));
    $this->assertEqual(
      $t + 3600,
      PhorgeTime::parseLocalTime('+1 hour', $v));

    unset($time);

    $t = 1370239200; // 2013-06-02 23:00:00 -0700
    $time = PhorgeTime::pushTime($t, 'America/Los_Angeles');

    // For the UTC user, midnight was 6 hours ago because it's early in the
    // morning for htem. For the PDT user, midnight was 23 hours ago.
    $this->assertEqual(
      $t + (-6 * 3600) + 60,
      PhorgeTime::parseLocalTime('12:01:00 AM', $u));
    $this->assertEqual(
      $t + (-23 * 3600) + 60,
      PhorgeTime::parseLocalTime('12:01:00 AM', $v));

    unset($time);
  }

}
