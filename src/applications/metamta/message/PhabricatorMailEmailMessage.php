<?php

final class PhabricatorMailEmailMessage
  extends PhabricatorMailExternalMessage {

  const MESSAGETYPE = 'email';

  private $fromAddress;
  private $replyToAddress;
  private $toAddresses = array();
  private $ccAddresses = array();
  private $headers = array();
  private $attachments = array();
  private $subject;
  private $textBody;
  private $htmlBody;

  public function newMailMessageEngine() {
    return new PhabricatorMailEmailEngine();
  }

  public function setFromAddress(PhutilEmailAddress $from_address) {
    $this->fromAddress = $from_address;
    return $this;
  }

  public function getFromAddress() {
    return $this->fromAddress;
  }

  public function setReplyToAddress(PhutilEmailAddress $address) {
    $this->replyToAddress = $address;
    return $this;
  }

  public function getReplyToAddress() {
    return $this->replyToAddress;
  }

  /**
   * @param array<PhutilEmailAddress> $addresses
   */
  public function setToAddresses(array $addresses) {
    assert_instances_of($addresses, PhutilEmailAddress::class);
    $this->toAddresses = $addresses;
    return $this;
  }

  public function getToAddresses() {
    return $this->toAddresses;
  }

  /**
   * @param array<PhutilEmailAddress> $addresses
   */
  public function setCCAddresses(array $addresses) {
    assert_instances_of($addresses, PhutilEmailAddress::class);
    $this->ccAddresses = $addresses;
    return $this;
  }

  public function getCCAddresses() {
    return $this->ccAddresses;
  }

  /**
   * @param array<PhabricatorMailHeader> $headers
   */
  public function setHeaders(array $headers) {
    assert_instances_of($headers, PhabricatorMailHeader::class);
    $this->headers = $headers;
    return $this;
  }

  public function getHeaders() {
    return $this->headers;
  }

  /**
   * @param array<PhabricatorMailAttachment> $attachments
   */
  public function setAttachments(array $attachments) {
    assert_instances_of($attachments, PhabricatorMailAttachment::class);
    $this->attachments = $attachments;
    return $this;
  }

  public function getAttachments() {
    return $this->attachments;
  }

  public function setSubject($subject) {
    $this->subject = $subject;
    return $this;
  }

  public function getSubject() {
    return $this->subject;
  }

  public function setTextBody($text_body) {
    $this->textBody = $text_body;
    return $this;
  }

  public function getTextBody() {
    return $this->textBody;
  }

  public function setHTMLBody($html_body) {
    $this->htmlBody = $html_body;
    return $this;
  }

  public function getHTMLBody() {
    return $this->htmlBody;
  }

}
