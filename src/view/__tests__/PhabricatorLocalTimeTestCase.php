<?php

final class PhabricatorLocalTimeTestCase extends PhabricatorTestCase {

  protected function getPhabricatorTestCaseConfiguration() {
    return array(
      self::PHABRICATOR_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testLocalTimeFormatting() {
    $user = $this->generateNewTestUser();
    $user->overrideTimezoneIdentifier('America/Los_Angeles');

    $utc = $this->generateNewTestUser();
    $utc->overrideTimezoneIdentifier('UTC');

    $this->assertEqual(
      'Jan 1 2000, 12:00 AM',
      vixon_datetime(946684800, $utc),
      pht('Datetime formatting'));
    $this->assertEqual(
      'Jan 1 2000',
      vixon_date(946684800, $utc),
      pht('Date formatting'));
    $this->assertEqual(
      '12:00 AM',
      vixon_time(946684800, $utc),
      pht('Time formatting'));

    $this->assertEqual(
      'Dec 31 1999, 4:00 PM',
      vixon_datetime(946684800, $user),
      pht('Localization'));

    $this->assertEqual(
      '',
      vixon_datetime(0, $user),
      pht('Missing epoch should fail gracefully'));
  }

}
