<?php

final class PhorgeUserEditorTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testRegistrationEmailOK() {
    $env = PhorgeEnv::beginScopedEnv();
    $env->overrideEnvConfig('auth.email-domains', array('example.com'));

    $this->registerUser(
      'PhorgeUserEditorTestCaseOK',
      'PhorgeUserEditorTest@example.com');

    $this->assertTrue(true);
  }

  public function testRegistrationEmailInvalid() {
    $env = PhorgeEnv::beginScopedEnv();
    $env->overrideEnvConfig('auth.email-domains', array('example.com'));

    $prefix = str_repeat('a', PhorgeUserEmail::MAX_ADDRESS_LENGTH);
    $email = $prefix.'@evil.com@example.com';

    try {
      $this->registerUser('PhorgeUserEditorTestCaseInvalid', $email);
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue($caught instanceof Exception);
  }

  public function testRegistrationEmailDomain() {
    $env = PhorgeEnv::beginScopedEnv();
    $env->overrideEnvConfig('auth.email-domains', array('example.com'));

    $caught = null;
    try {
      $this->registerUser(
        'PhorgeUserEditorTestCaseDomain',
        'PhorgeUserEditorTest@whitehouse.gov');
    } catch (Exception $ex) {
      $caught = $ex;
    }

    $this->assertTrue($caught instanceof Exception);
  }

  public function testRegistrationEmailApplicationEmailCollide() {
    $app_email = 'bugs@whitehouse.gov';
    $app_email_object =
      PhorgeMetaMTAApplicationEmail::initializeNewAppEmail(
        $this->generateNewTestUser());
    $app_email_object->setAddress($app_email);
    $app_email_object->setApplicationPHID('test');
    $app_email_object->save();

    $caught = null;
    try {
      $this->registerUser(
        'PhorgeUserEditorTestCaseDomain',
        $app_email);
    } catch (Exception $ex) {
      $caught = $ex;
    }
    $this->assertTrue($caught instanceof Exception);
  }

  private function registerUser($username, $email) {
    $user = id(new PhorgeUser())
      ->setUsername($username)
      ->setRealname($username);

    $email = id(new PhorgeUserEmail())
      ->setAddress($email)
      ->setIsVerified(0);

    id(new PhorgeUserEditor())
      ->setActor($user)
      ->createNewUser($user, $email);
  }

}
