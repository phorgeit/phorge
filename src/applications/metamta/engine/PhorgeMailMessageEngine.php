<?php

abstract class PhorgeMailMessageEngine
  extends Phobject {

  private $mailer;
  private $mail;
  private $actors = array();
  private $preferences;

  final public function setMailer(PhorgeMailAdapter $mailer) {

    $this->mailer = $mailer;
    return $this;
  }

  final public function getMailer() {
    return $this->mailer;
  }

  final public function setMail(PhorgeMetaMTAMail $mail) {
    $this->mail = $mail;
    return $this;
  }

  final public function getMail() {
    return $this->mail;
  }

  final public function setActors(array $actors) {
    assert_instances_of($actors, 'PhorgeMetaMTAActor');
    $this->actors = $actors;
    return $this;
  }

  final public function getActors() {
    return $this->actors;
  }

  final public function getActor($phid) {
    return idx($this->actors, $phid);
  }

  final public function setPreferences(
    PhorgeUserPreferences $preferences) {
    $this->preferences = $preferences;
    return $this;
  }

  final public function getPreferences() {
    return $this->preferences;
  }

}
