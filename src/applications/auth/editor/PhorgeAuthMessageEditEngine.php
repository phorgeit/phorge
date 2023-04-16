<?php

final class PhorgeAuthMessageEditEngine
  extends PhorgeEditEngine {

  private $messageType;

  const ENGINECONST = 'auth.message';

  public function isEngineConfigurable() {
    return false;
  }

  public function getEngineName() {
    return pht('Auth Messages');
  }

  public function getSummaryHeader() {
    return pht('Edit Auth Messages');
  }

  public function getSummaryText() {
    return pht('This engine is used to edit authentication messages.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  public function setMessageType(PhorgeAuthMessageType $type) {
    $this->messageType = $type;
    return $this;
  }

  public function getMessageType() {
    return $this->messageType;
  }

  protected function newEditableObject() {
    $type = $this->getMessageType();

    if ($type) {
      $message = PhorgeAuthMessage::initializeNewMessage($type);
    } else {
      $message = new PhorgeAuthMessage();
    }

    return $message;
  }

  protected function newObjectQuery() {
    return new PhorgeAuthMessageQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Auth Message');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Auth Message');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Auth Message');
  }

  protected function getObjectEditShortText($object) {
    return $object->getObjectName();
  }

  protected function getObjectCreateShortText() {
    return pht('Create Auth Message');
  }

  protected function getObjectName() {
    return pht('Auth Message');
  }

  protected function getEditorURI() {
    return '/auth/message/edit/';
  }

  protected function getObjectCreateCancelURI($object) {
    return '/auth/message/';
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function getCreateNewObjectPolicy() {
    return $this->getApplication()->getPolicy(
      AuthManageProvidersCapability::CAPABILITY);
  }

  protected function buildCustomEditFields($object) {
    return array(
      id(new PhorgeRemarkupEditField())
        ->setKey('messageText')
        ->setTransactionType(
          PhorgeAuthMessageTextTransaction::TRANSACTIONTYPE)
        ->setLabel(pht('Message Text'))
        ->setDescription(pht('Custom text for the message.'))
        ->setValue($object->getMessageText()),
    );
  }

}
