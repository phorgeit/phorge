<?php

final class CalendarImportTestCase extends PhabricatorTestCase {

  protected function getPhabricatorTestCaseConfiguration() {
    return array(
      self::PHABRICATOR_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  // Indexes of the "expectedInviteesTests" test.
  const INVITED_USER = 0;
  const INVITED_EXPECTED = 1;
  const INVITED_RESULT = 2;

  public function testIcsFileImportWithGuestThatIsHost() {
    $alice_unverified =
      $this->generateTestUserWithVerifiedMail(
        'alice@example.com',
        0);
    $lincoln_verified =
      $this->generateTestUserWithVerifiedMail(
        'a.lincoln@example.com',
        1);
    $alien_unverified =
      $this->generateTestUserWithVerifiedMail(
        'alien.unferified@example.com',
        0);
    $alien_verified =
      $this->generateTestUserWithVerifiedMail(
        'alien.verified@example.com',
        1);

    // Tests are event-based. Each event has their expected invitees.
    $tests = array(
      // Test zero. Alice imports an event with A.Lincoln.
      array(
        'test' => pht('alice invites a.lincoln via verified email'),
        'file' => 'simple-event-alincoln-guest.ics',
        'fileAuthor' => $alice_unverified,
        'expectedInvitees' => 3,
        'expectedInviteesTests' => array(
          // Documentation:
          //   Array 0 (INVITED_USER):     User object
          //   Array 1 (INVITED_EXPECTED): Presence (bool)
          array($lincoln_verified, false),
          array($alice_unverified, false),
          array($alien_unverified, false),
          array($alien_verified, false),
        ),
      ),
      // Test one. A.Lincoln imports an event with A.Lincoln.
      array(
        'test' => pht('a.lincoln self-invite via verified email'),
        'file' => 'simple-event-alincoln-guest.ics',
        'fileAuthor' => $lincoln_verified,
        'expectedInvitees' => 3,
        'expectedInviteesTests' => array(
          array($lincoln_verified, true), // Self-invitation. T15564
          array($alice_unverified, false),
          array($alien_unverified, false),
          array($alien_verified, false),
        ),
      ),
    );

    foreach ($tests as $test) {
      $this->runIcsFileImportTestWithExpectedResults(
        $test['test'],
        $test['file'],
        $test['fileAuthor'],
        $test['expectedInvitees'],
        $test['expectedInviteesTests']);
    }
  }

  private function runIcsFileImportTestWithExpectedResults(
    $test, $file, $importer_author, $expecteds, $invitees_tests) {

    $ics_path = __DIR__.'/events/'.$file;

    // Prepare a calendar import.
    $import_type = new PhabricatorCalendarICSFileImportEngine();
    $calendar_import = PhabricatorCalendarImport::initializeNewCalendarImport(
      $importer_author,
      clone $import_type);

    // Create the File containing the ICS example.
    $file_data = Filesystem::readFile($ics_path);
    $file_test_engine = new PhabricatorTestStorageEngine();
    $file_params = array(
      'name' => $file,
      'viewPolicy' => PhabricatorPolicies::POLICY_USER,
      'authorPHID' => $importer_author->getPHID(),
      'storageEngines' => array($file_test_engine),
    );
    $file_up = PhabricatorFile::newFromFileData($file_data, $file_params);

    // Create a calendar import with our ICS file.
    $import_xactions = array();
    $import_xactions[] = id(new PhabricatorCalendarImportTransaction())
      ->setTransactionType(
        PhabricatorCalendarImportICSFileTransaction::TRANSACTIONTYPE)
      ->setNewValue($file_up->getPHID());

    // Persist the calendar import and get it.
    id(new PhabricatorCalendarImportEditor())
      ->setActor($importer_author)
      ->setContentSource($this->newContentSource())
      ->applyTransactions($calendar_import, $import_xactions);

    $import_type->importEventsFromSource(
      $importer_author,
      $calendar_import,
      false);

    // Find imported events from the perspective of the importer author itself.
    // So we check if we gained some extra people email visibility by mistake.
    // The backend does not support attachInvitees() and it's done by default.
    $events = (new PhabricatorCalendarEventQuery())
      ->setViewer($importer_author)
      ->withImportSourcePHIDs(array($calendar_import->getPHID()))
      ->execute();

    // At the moment test cases are hardcoded with one event.
    $this->assertEqual(
      1,
      count($events),
      pht('Unexpected events in file "%s" on test "%s"',
        $file,
        $test));

    // Take the first event.
    $event = head($events);

    // How many people we invited in this event.
    $this->assertEqual(
      $expecteds,
      count($event->getInvitees()),
      pht('Unexpected invitees in file "%s" on test "%s"', $file, $test));

    foreach ($invitees_tests as $invitees_test) {
      $this->assertMatchingInvitees($test, $file, $event, $invitees_tests);
    }

    $event->delete();
  }

  /**
   * Check if the invitees matches.
   */
  private function assertMatchingInvitees($test, $file, $event, $expecteds) {

    // Index what we expect, by user PHID.
    $expected_users_by_phid = [];
    foreach ($expecteds as $expected_invited_data) {
        $user_phid = $expected_invited_data[self::INVITED_USER]
          ->getPHID();
        $expected_users_by_phid[$user_phid]
          = $expected_invited_data;
    }

    // Get current invitees (mixed between "PHID-CXNV" and "PHID-USER").
    $actuals_phids_mixed = mpull($event->getInvitees(), 'getInviteePHID');

    // Get just actual users.
    $actual_users = (new PhabricatorUser())->loadAllWhere(
      'phid IN (%Ls)',
      $actuals_phids_mixed);
    $actual_users = mpull($actual_users, null, 'getPHID');

    // Map actual users with the expected ones.
    foreach ($actual_users as $actual_user) {
        $user_phid = $actual_user->getPHID();
        $found = isset($expected_users_by_phid[$user_phid]);
        if (!$found) {
            $expected_users_by_phid[$user_phid] = array();
        }
        $expected_users_by_phid[$user_phid][self::INVITED_RESULT]
          = $found;
    }

//   In the future it may be useful to also check external users
//   by their email. In case, start from here!
//   but note that the 'getURI()' returns 'mailto:' stuff.
//  $actual_externals = (new PhabricatorCalendarExternalInvitee())
//      ->loadAllWhere(
//      'phid IN (%Ls)',
//      $actuals_phids_mixed);
//  $actual_externals = mpull($actual_externals, null, 'getURI');

    // Check results (matched or not).
    foreach ($expected_users_by_phid as $phid => $expecteds_data) {
      $expected = idx($expecteds_data, self::INVITED_EXPECTED, null);
      $result = idx($expecteds_data, self::INVITED_RESULT, false);
      $user = idx($expecteds_data, self::INVITED_USER, null);
      if ($expected !== null) {
        $this->assertEqual(
          $expected,
          $result,
          pht('Unexpected presence of user "%s" in file "%s" on test "%s"',
            $user == null ? '(unknown)' : $user->loadPrimaryEmailAddress(),
            $file,
            $test));
      }
    }
  }

  /**
   * Generate a test user with a specific verified (or not) email.
   * @param string $mail Email address
   * @param int    $is_verified 0: unverified, 1: verified
   * @return PhabricatorUser
   */
  private function generateTestUserWithVerifiedMail($mail, $is_verified) {
    $user = $this->generateNewTestUser();

    // Set our primary address as verified or not.
    $email = id(new PhabricatorUserEmail())->loadOneWhere(
      'userPHID = %s',
      $user->getPHID());

    $email->setAddress($mail);
    $email->setIsVerified($is_verified);
    $email->setIsPrimary(true);
    $email->save();

    return $user;
  }

}
