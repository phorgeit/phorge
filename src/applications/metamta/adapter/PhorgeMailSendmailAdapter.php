<?php

final class PhorgeMailSendmailAdapter
  extends PhorgeMailAdapter {

  const ADAPTERTYPE = 'sendmail';

  public function getSupportedMessageTypes() {
    return array(
      PhorgeMailEmailMessage::MESSAGETYPE,
    );
  }

  public function supportsMessageIDHeader() {
    return $this->guessIfHostSupportsMessageID(
      $this->getOption('message-id'),
      null);
  }

  protected function validateOptions(array $options) {
    PhutilTypeSpec::checkMap(
      $options,
      array(
        'message-id' => 'bool|null',
      ));
  }

  public function newDefaultOptions() {
    return array(
      'message-id' => null,
    );
  }

  /**
   * @phutil-external-symbol class PHPMailerLite
   */
  public function sendMessage(PhorgeMailExternalMessage $message) {
    $root = phutil_get_library_root('phorge');
    $root = dirname($root);
    require_once $root.'/externals/phpmailer/class.phpmailer-lite.php';

    $mailer = PHPMailerLite::newFromMessage($message);
    $mailer->Send();
  }

}
