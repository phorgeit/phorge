<?php

abstract class PhorgeEditorExtension
  extends Phobject {

  private $viewer;
  private $editor;
  private $object;

  final public function getExtensionKey() {
    return $this->getPhobjectClassConstant('EXTENSIONKEY');
  }

  final public function setEditor(
    PhorgeApplicationTransactionEditor $editor) {
    $this->editor = $editor;
    return $this;
  }

  final public function getEditor() {
    return $this->editor;
  }

  final public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  final public function getViewer() {
    return $this->viewer;
  }

  final public function setObject(
    PhorgeApplicationTransactionInterface $object) {
    $this->object = $object;
    return $this;
  }

  final public static function getAllExtensions() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getExtensionKey')
      ->execute();
  }

  abstract public function getExtensionName();

  public function supportsObject(
    PhorgeApplicationTransactionEditor $editor,
    PhorgeApplicationTransactionInterface $object) {
    return true;
  }

  public function validateTransactions($object, array $xactions) {
    return array();
  }

  final protected function newTransactionError(
    PhorgeApplicationTransaction $xaction,
    $title,
    $message) {
    return new PhorgeApplicationTransactionValidationError(
      $xaction->getTransactionType(),
      $title,
      $message,
      $xaction);
  }

  final protected function newRequiredTransasctionError(
    PhorgeApplicationTransaction $xaction,
    $message) {
    return $this->newError($xaction, pht('Required'), $message)
      ->setIsMissingFieldError(true);
  }

  final protected function newInvalidTransactionError(
    PhorgeApplicationTransaction $xaction,
    $message) {
    return $this->newTransactionError($xaction, pht('Invalid'), $message);
  }


}
