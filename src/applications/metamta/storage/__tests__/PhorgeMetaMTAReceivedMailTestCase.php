<?php

final class PhorgeMetaMTAReceivedMailTestCase extends PhorgeTestCase {

  protected function getPhorgeTestCaseConfiguration() {
    return array(
      self::PHORGE_TESTCONFIG_BUILD_STORAGE_FIXTURES => true,
    );
  }

  public function testDropSelfMail() {
    $mail = new PhorgeMetaMTAReceivedMail();
    $mail->setHeaders(
      array(
        'X-Phorge-Sent-This-Message' => 'yes',
      ));
    $mail->save();

    $mail->processReceivedMail();

    $this->assertEqual(
      MetaMTAReceivedMailStatus::STATUS_FROM_PHORGE,
      $mail->getStatus());
  }


  public function testDropDuplicateMail() {
    $mail_a = new PhorgeMetaMTAReceivedMail();
    $mail_a->setHeaders(
      array(
        'Message-ID' => 'test@example.com',
      ));
    $mail_a->save();

    $mail_b = new PhorgeMetaMTAReceivedMail();
    $mail_b->setHeaders(
      array(
        'Message-ID' => 'test@example.com',
      ));
    $mail_b->save();

    $mail_a->processReceivedMail();
    $mail_b->processReceivedMail();

    $this->assertEqual(
      MetaMTAReceivedMailStatus::STATUS_DUPLICATE,
      $mail_b->getStatus());
  }

  public function testDropUnreceivableMail() {
    $user = $this->generateNewTestUser()
      ->save();

    $mail = new PhorgeMetaMTAReceivedMail();
    $mail->setHeaders(
      array(
        'Message-ID' => 'test@example.com',
        'To'         => 'does+not+exist@example.com',
        'From'        => $user->loadPrimaryEmail()->getAddress(),
      ));
    $mail->setBodies(
      array(
        'text' => 'test',
      ));
    $mail->save();

    $mail->processReceivedMail();

    $this->assertEqual(
      MetaMTAReceivedMailStatus::STATUS_NO_RECEIVERS,
      $mail->getStatus());
  }

  public function testDropUnknownSenderMail() {
    $this->setManiphestCreateEmail();

    $mail = new PhorgeMetaMTAReceivedMail();
    $mail->setHeaders(
      array(
        'Message-ID' => 'test@example.com',
        'To'         => 'bugs@example.com',
        'From'       => 'does+not+exist@example.com',
      ));
    $mail->setBodies(
      array(
        'text' => 'test',
      ));
    $mail->save();

    $mail->processReceivedMail();

    $this->assertEqual(
      MetaMTAReceivedMailStatus::STATUS_UNKNOWN_SENDER,
      $mail->getStatus());
  }


  public function testDropDisabledSenderMail() {
    $this->setManiphestCreateEmail();

    $user = $this->generateNewTestUser()
      ->setIsDisabled(true)
      ->save();

    $mail = new PhorgeMetaMTAReceivedMail();
    $mail->setHeaders(
      array(
        'Message-ID'  => 'test@example.com',
        'From'        => $user->loadPrimaryEmail()->getAddress(),
        'To'          => 'bugs@example.com',
      ));
    $mail->setBodies(
      array(
        'text' => 'test',
      ));
    $mail->save();

    $mail->processReceivedMail();

    $this->assertEqual(
      MetaMTAReceivedMailStatus::STATUS_DISABLED_SENDER,
      $mail->getStatus());
  }

  private function setManiphestCreateEmail() {
    $maniphest_app = new PhorgeManiphestApplication();
    try {
      id(new PhorgeMetaMTAApplicationEmail())
        ->setApplicationPHID($maniphest_app->getPHID())
        ->setAddress('bugs@example.com')
        ->setConfigData(array())
        ->save();
    } catch (AphrontDuplicateKeyQueryException $ex) {}
  }

}
