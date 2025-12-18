<?php

final class PhabricatorUserEditorTestCase extends PhabricatorTestCase {

  protected function getPhabricatorTestCaseConfiguration() {
    return array(
      self::PHABRICATOR_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testRegistrationEmailOK() {
    $env = PhabricatorEnv::beginScopedEnv();
    $env->overrideEnvConfig('auth.email-domains', array('example.com'));

    $this->registerUser(
      'PhabricatorUserEditorTestCaseOK',
      'PhabricatorUserEditorTest@example.com');

    $this->assertTrue(true);
  }

  public function testRegistrationEmailInvalid() {
    $env = PhabricatorEnv::beginScopedEnv();
    $env->overrideEnvConfig('auth.email-domains', array('example.com'));

    $prefix = str_repeat('a', PhabricatorUserEmail::MAX_ADDRESS_LENGTH);
    $email = $prefix.'@evil.com@example.com';

    try {
      $this->registerUser('PhabricatorUserEditorTestCaseInvalid', $email);
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue($caught instanceof Exception);
  }

  public function testRegistrationEmailDomain() {
    $env = PhabricatorEnv::beginScopedEnv();
    $env->overrideEnvConfig('auth.email-domains', array('example.com'));

    $caught = null;
    try {
      $this->registerUser(
        'PhabricatorUserEditorTestCaseDomain',
        'PhabricatorUserEditorTest@whitehouse.gov');
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue($caught instanceof Exception);
  }

  public function testRegistrationEmailApplicationEmailCollide() {
    $app_email = 'bugs@whitehouse.gov';
    $app_email_object =
      PhabricatorMetaMTAApplicationEmail::initializeNewAppEmail(
        $this->generateNewTestUser());
    $app_email_object->setAddress($app_email);
    $app_email_object->setApplicationPHID('test');
    $app_email_object->save();

    $caught = null;
    try {
      $this->registerUser(
        'PhabricatorUserEditorTestCaseDomain',
        $app_email);
    } catch (Exception $ex) {
      $caught = $ex;
    }
    $this->assertTrue($caught instanceof Exception);
  }

  private function registerUser($username, $email) {
    $user = id(new PhabricatorUser())
      ->setUsername($username)
      ->setRealname($username);

    $email = id(new PhabricatorUserEmail())
      ->setAddress($email)
      ->setIsVerified(0);

    id(new PhabricatorUserEditor())
      ->setActor($user)
      ->createNewUser($user, $email);
  }

  /**
   * Test the destruction of one profile picture in use.
   * This test covers the "before-destruction" engine called
   * 'PeopleProfilePictureBeforeDestructionEngineExtension'.
   */
  public function testProfilePictureDestruction() {
    // Create some users with different profile pictures.
    // Note that 'avatar.png' is Psyduck, btw.
    $user1 = $this->generateNewTestUser();
    $user2 = $this->generateNewTestUser();
    $user3 = $this->generateNewTestUser();
    $pic1 = $this->attachBuiltinImage($user1, 'avatar.png');
    $pic2 = $this->attachBuiltinImage($user2, 'user2.png');
    $pic3 = $this->attachBuiltinImage($user3, 'user3.png');

    // Base test.
    $this->assertEqual($pic1->getPHID(), $user1->getProfileImagePHID());
    $this->assertEqual($pic2->getPHID(), $user2->getProfileImagePHID());
    $this->assertEqual($pic3->getPHID(), $user3->getProfileImagePHID());

    // Destroy the profile image of user1.
    // Our intention is to test this:
    //   ./bin/remove destroy $PIC1
    // As desired side-effect, the "before destruction engine"
    // 'PeopleProfilePictureBeforeDestructionEngineExtension'
    // is executed too, to orphanize the profile image of $user1.
    // All other users must remain untouched.
    $engine = new PhabricatorDestructionEngine();
    $engine->destroyObject($pic1);
    $user1 = $user1->reload();
    $user2 = $user2->reload();
    $user3 = $user3->reload();
    $this->assertEqual(null, $user1->getProfileImagePHID());
    $this->assertEqual($pic2->getPHID(), $user2->getProfileImagePHID());
    $this->assertEqual($pic3->getPHID(), $user3->getProfileImagePHID());

    // Test if pic2 can be destroyed, even if the user clicked 'Detach'.
    // This test can be potentially removed if the related micro-optimizations
    // are removed from the engine
    // (PeopleProfilePictureBeforeDestructionEngineExtension).
    // See code about https://we.phorge.it/T16080.
    // Also, test again that pic3 is untouched.
    $this->detachFileFromAllObjects($pic2); // Click 'Detach'.
    $engine->destroyObject($pic2);
    $user2 = $user2->reload();
    $user3 = $user3->reload();
    $this->assertEqual(null, $user2->getProfileImagePHID());
    $this->assertEqual($pic3->getPHID(), $user3->getProfileImagePHID());
  }

  /**
   * Assign a profile picture to one user, starting from a builtin image.
   * Get the resulting profile picture.
   * @param PhabricatorUser $user User receiving the profile picture.
   * @param string $image Builtin image name used as starting point.
   * @return PhabricatorFile File transform used as profile picture.
   */
  private function attachBuiltinImage(
    PhabricatorUser $user,
    string $image): PhabricatorFile {
    // Code credit: PhabricatorPeopleProfilePictureController

    $file = PhabricatorFile::loadBuiltin($user, $image);
    $xform = PhabricatorFileTransform::getTransformByKey(
      PhabricatorFileThumbnailTransform::TRANSFORM_PROFILE);
    $xformed = $xform->executeTransform($file);

    // Assign the profile image to the user.
    $xformed->attachToObject($user->getPHID());
    $user->setProfileImagePHID($xformed->getPHID());
    $user->save();

    return $xformed;
  }

  /**
   * Detach a file from all objects.
   * For example if the file was shown as attachment in the user profile page,
   * this file will be not anymore, like you clicked on the 'Detach' button.
   * @param PhabricatorFile $file
   * @return void
   */
  private function detachFileFromAllObjects(PhabricatorFile $file) {
    $table = new PhabricatorFileAttachment();
    $attachments = $table->loadAllWhere(
      'filePHID = %s',
      $file->getPHID());
    foreach ($attachments as $attachment) {
      $attachment->delete();
    }
  }

}
