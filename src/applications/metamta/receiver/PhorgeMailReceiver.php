<?php

abstract class PhorgeMailReceiver extends Phobject {

  private $viewer;
  private $sender;

  final public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  final public function getViewer() {
    return $this->viewer;
  }

  final public function setSender(PhorgeUser $sender) {
    $this->sender = $sender;
    return $this;
  }

  final public function getSender() {
    return $this->sender;
  }

  abstract public function isEnabled();
  abstract public function canAcceptMail(
    PhorgeMetaMTAReceivedMail $mail,
    PhutilEmailAddress $target);

  abstract protected function processReceivedMail(
    PhorgeMetaMTAReceivedMail $mail,
    PhutilEmailAddress $target);

  final public function receiveMail(
    PhorgeMetaMTAReceivedMail $mail,
    PhutilEmailAddress $target) {
    $this->processReceivedMail($mail, $target);
  }

}
