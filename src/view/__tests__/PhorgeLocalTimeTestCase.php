<?php

final class PhorgeLocalTimeTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testLocalTimeFormatting() {
    $user = $this->generateNewTestUser();
    $user->overrideTimezoneIdentifier('America/Los_Angeles');

    $utc = $this->generateNewTestUser();
    $utc->overrideTimezoneIdentifier('UTC');

    $this->assertEqual(
      'Jan 1 2000, 12:00 AM',
      phorge_datetime(946684800, $utc),
      pht('Datetime formatting'));
    $this->assertEqual(
      'Jan 1 2000',
      phorge_date(946684800, $utc),
      pht('Date formatting'));
    $this->assertEqual(
      '12:00 AM',
      phorge_time(946684800, $utc),
      pht('Time formatting'));

    $this->assertEqual(
      'Dec 31 1999, 4:00 PM',
      phorge_datetime(946684800, $user),
      pht('Localization'));

    $this->assertEqual(
      '',
      phorge_datetime(0, $user),
      pht('Missing epoch should fail gracefully'));
  }

}
