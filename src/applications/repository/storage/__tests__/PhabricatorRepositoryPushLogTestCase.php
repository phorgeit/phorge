<?php

final class PhabricatorRepositoryPushLogTestCase
  extends PhabricatorTestCase {

  protected function getPhabricatorTestCaseConfiguration() {
    return array(
      self::PHABRICATOR_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testSavePushLogVariants(): void {
    $viewer = $this->generateNewTestUser();

    // Write a push event once, and recycle it for multiple tests.
    $event = $this->getTestPushEvent($viewer)
      ->save();

    // Test query class.
    // Get same object back or scream violently.
    $event = id(new PhabricatorRepositoryPushEventQuery())
      ->setViewer($viewer)
      ->withIDs(array($event->getID()))
      ->withPHIDs(array($event->getPHID()))
      ->withRepositoryPHIDs(array($event->getRepositoryPHID()))
      ->withPusherPHIDs(array($event->getPusherPHID()))
      ->executeOne();

    // Test push commit.
    $pushlog = PhabricatorRepositoryPushLog::initializeNewLog($viewer)
      ->setEpoch(1723143134)
      ->setPushEventPHID($event->getPHID())
      ->setRepositoryPHID($event->getRepositoryPHID())
      ->attachRepository($event->getRepository())
      ->setRefType('commit')
      ->setRefOld(null)
      ->setRefNew('d0c1134cc3bc09cdaf853f7316ca4a283216903b')
      ->setMergeBase(null)
      ->setChangeFlags(1)
      ->setDevicePHID(null)
      ->save();

    $this->assertTrue(is_string($pushlog->getPHID()));
    $this->assertTrue(is_int($pushlog->getId()));
    $this->assertEqual(
      'd0c1134cc3bc09cdaf853f7316ca4a283216903b',
      $pushlog->getRefNew());
    $this->assertEqual(null, $pushlog->getRefOld());
    $this->assertEqual(
      null,
      $pushlog->getRefOldShort(),
      'Test getRefOldShort() still working with a null ref old');

    // Test push branch.
    $pushlog = PhabricatorRepositoryPushLog::initializeNewLog($viewer)
      ->setEpoch(1723143134)
      ->setPushEventPHID($event->getPHID())
      ->setRepositoryPHID($event->getRepositoryPHID())
      ->attachRepository($event->getRepository())
      ->setPusherPHID($event->getPusherPHID())
      ->setRefType('branch')
      ->setRefNameHash('jF7urH7zIj8U')
      ->setRefNameRaw('arcpatch-D25738')
      ->setRefNameEncoding('utf8')
      ->setRefOld('0000000000000000000000000000000000000000')
      ->setRefNew('ec01175a2135817fbcc050154b9bc87b96cdfd41')
      ->setMergeBase(null)
      ->setChangeFlags(1)
      ->setDevicePHID(null)
      ->save();

    $this->assertTrue(is_string($pushlog->getPHID()));
    $this->assertTrue(is_int($pushlog->getId()));
    $this->assertEqual(
      '0000000000000000000000000000000000000000',
      $pushlog->getRefOld());
    $this->assertEqual(
      'ec01175a2135817fbcc050154b9bc87b96cdfd41',
      $pushlog->getRefNew());
    $this->assertTrue(
      strlen($pushlog->getRefOldShort()) < strlen($pushlog->getRefOld()),
      'Test getRefOldShort() shortening a git reference');
  }

  /**
   * Get a new unpersisted repository push event, for tests.
   */
  private function getTestPushEvent(PhabricatorUser $viewer):
    PhabricatorRepositoryPushEvent {

    $repository = $this->getTestRepositoryGit($viewer)
      ->save();

    return PhabricatorRepositoryPushEvent::initializeNewEvent($viewer)
      ->setRepositoryPHID($repository->getPHID())
      ->setEpoch(1723143134)
      ->setPusherPHID($viewer->getPHID())
      ->setRemoteAddress('127.0.0.1')
      ->setRemoteProtocol('http')
      ->setRejectCode(0)
      ->setRejectDetails(null)
      ->setRequestIdentifier(null)
      ->setWriteWait(null)
      ->setReadWait(null)
      ->setHostWait(null)
      ->setHookWait(328314);
  }

  /**
   * Get a new unpersisted git repository, for tests.
   */
  private function getTestRepositoryGit(PhabricatorUser $viewer):
    PhabricatorRepository {

    $git = PhabricatorRepositoryType::REPOSITORY_TYPE_GIT;

    return PhabricatorRepository::initializeNewRepository($viewer)
      ->setVersionControlSystem($git)
      ->setName('Test repository');
  }

}
