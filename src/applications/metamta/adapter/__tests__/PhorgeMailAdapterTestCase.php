<?php

final class PhorgeMailAdapterTestCase
  extends PhorgeTestCase {

  public function testSupportsMessageID() {
    $cases = array(
      array(
        pht('Amazon SES'),
        false,
        new PhorgeMailAmazonSESAdapter(),
        array(
          'access-key' => 'test',
          'secret-key' => 'test',
          'region' => 'test',
          'endpoint' => 'test',
        ),
      ),

      array(
        pht('Mailgun'),
        true,
        new PhorgeMailMailgunAdapter(),
        array(
          'api-key' => 'test',
          'domain' => 'test',
          'api-hostname' => 'test',
        ),
      ),

      array(
        pht('Sendmail'),
        true,
        new PhorgeMailSendmailAdapter(),
        array(),
      ),

      array(
        pht('Sendmail (Explicit Config)'),
        false,
        new PhorgeMailSendmailAdapter(),
        array(
          'message-id' => false,
        ),
      ),

      array(
        pht('SMTP (Local)'),
        true,
        new PhorgeMailSMTPAdapter(),
        array(),
      ),

      array(
        pht('SMTP (Local + Explicit)'),
        false,
        new PhorgeMailSMTPAdapter(),
        array(
          'message-id' => false,
        ),
      ),

      array(
        pht('SMTP (AWS)'),
        false,
        new PhorgeMailSMTPAdapter(),
        array(
          'host' => 'test.amazonaws.com',
        ),
      ),

      array(
        pht('SMTP (AWS + Explicit)'),
        true,
        new PhorgeMailSMTPAdapter(),
        array(
          'host' => 'test.amazonaws.com',
          'message-id' => true,
        ),
      ),

    );

    foreach ($cases as $case) {
      list($label, $expect, $mailer, $options) = $case;

      $defaults = $mailer->newDefaultOptions();
      $mailer->setOptions($options + $defaults);

      $actual = $mailer->supportsMessageIDHeader();

      $this->assertEqual($expect, $actual, pht('Message-ID: %s', $label));
    }
  }


}
