<?php

final class PhorgeMailSMSMessage
  extends PhorgeMailExternalMessage {

  const MESSAGETYPE = 'sms';

  private $toNumber;
  private $textBody;

  public function newMailMessageEngine() {
    return new PhorgeMailSMSEngine();
  }

  public function setToNumber(PhorgePhoneNumber $to_number) {
    $this->toNumber = $to_number;
    return $this;
  }

  public function getToNumber() {
    return $this->toNumber;
  }

  public function setTextBody($text_body) {
    $this->textBody = $text_body;
    return $this;
  }

  public function getTextBody() {
    return $this->textBody;
  }

}
