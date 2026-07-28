<?php

final class PhabricatorAuthMessageEditEngine
  extends PhabricatorEditEngine {

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
    return PhabricatorAuthApplication::class;
  }

  public function setMessageType(PhabricatorAuthMessageType $type) {
    $this->messageType = $type;
    return $this;
  }

  public function getMessageType() {
    return $this->messageType;
  }

  protected function newEditableObject() {
    $type = $this->getMessageType();

    if ($type) {
      $message = PhabricatorAuthMessage::initializeNewMessage($type);
    } else {
      $message = new PhabricatorAuthMessage();
    }

    return $message;
  }

  protected function newObjectQuery() {
    return new PhabricatorAuthMessageQuery();
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

  /**
   * Build custom edit fields.
   * @param PhabricatorAuthMessage $object
   * @return array Custom edit fields
   */
  protected function buildCustomEditFields($object) {
    $message_type = $object->getMessageType();
    $control_instructions = $message_type->getFullDescription();

    // When the current $value is null, it means you are going
    // to override the default message text.
    // In this case, easily tune the default message text,
    // instead of starting from scratch.
    $value = $object->getMessageText();
    if ($value === null) {
      $value = $message_type->getDefaultMessageText();
    }

    return array(
      id(new PhabricatorRemarkupEditField())
        ->setKey('messageText')
        ->setTransactionType(
          PhabricatorAuthMessageTextTransaction::TRANSACTIONTYPE)
        ->setLabel(pht('Message Text'))
        ->setDescription(pht('Custom text for the message.'))
        ->setControlInstructions($control_instructions)
        ->setValue($value),
    );
  }

}
