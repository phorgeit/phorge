<?php

final class FundInitiativeEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'fund.initiative';

  public function getEngineName() {
    return pht('Fund');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeFundApplication';
  }

  public function getSummaryHeader() {
    return pht('Configure Fund Forms');
  }

  public function getSummaryText() {
    return pht('Configure creation and editing forms in Fund.');
  }

  public function isEngineConfigurable() {
    return false;
  }

  protected function newEditableObject() {
    return FundInitiative::initializeNewInitiative($this->getViewer());
  }

  protected function newObjectQuery() {
    return new FundInitiativeQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New Initiative');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Initiative: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return $object->getName();
  }

  protected function getObjectCreateShortText() {
    return pht('Create Initiative');
  }

  protected function getObjectName() {
    return pht('Initiative');
  }

  protected function getObjectCreateCancelURI($object) {
    return $this->getApplication()->getApplicationURI('/');
  }

  protected function getEditorURI() {
    return $this->getApplication()->getApplicationURI('edit/');
  }

  protected function getObjectViewURI($object) {
    return $object->getViewURI();
  }

  protected function getCreateNewObjectPolicy() {
    return $this->getApplication()->getPolicy(
      FundCreateInitiativesCapability::CAPABILITY);
  }

  protected function buildCustomEditFields($object) {
    $viewer = $this->getViewer();
    $v_merchant = $object->getMerchantPHID();

    $merchants = id(new PhortuneMerchantQuery())
      ->setViewer($viewer)
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->execute();

    $merchant_options = array();
    foreach ($merchants as $merchant) {
      $merchant_options[$merchant->getPHID()] = pht(
        'Merchant %d %s',
        $merchant->getID(),
        $merchant->getName());
    }

    if ($v_merchant && empty($merchant_options[$v_merchant])) {
      $merchant_options = array(
        $v_merchant => pht('(Restricted Merchant)'),
      ) + $merchant_options;
    }

    $merchant_instructions = null;
    if (!$merchant_options) {
      $merchant_instructions = pht(
        'NOTE: You do not control any merchant accounts which can receive '.
        'payments from this initiative. When you create an initiative, '.
        'you need to specify a merchant account where funds will be paid '.
        'to. Create a merchant account in the Phortune application before '.
        'creating an initiative in Fund.');
    }

    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Initiative name.'))
        ->setConduitTypeDescription(pht('New initiative name.'))
        ->setTransactionType(
          FundInitiativeNameTransaction::TRANSACTIONTYPE)
        ->setValue($object->getName())
        ->setIsRequired(true),
      id(new PhorgeSelectEditField())
        ->setKey('merchantPHID')
        ->setLabel(pht('Merchant'))
        ->setDescription(pht('Merchant operating the initiative.'))
        ->setConduitTypeDescription(pht('New initiative merchant.'))
        ->setControlInstructions($merchant_instructions)
        ->setValue($object->getMerchantPHID())
        ->setTransactionType(
          FundInitiativeMerchantTransaction::TRANSACTIONTYPE)
        ->setOptions($merchant_options)
        ->setIsRequired(true),
      id(new PhorgeRemarkupEditField())
        ->setKey('description')
        ->setLabel(pht('Description'))
        ->setDescription(pht('Initiative long description.'))
        ->setConduitTypeDescription(pht('New initiative description.'))
        ->setTransactionType(
          FundInitiativeDescriptionTransaction::TRANSACTIONTYPE)
        ->setValue($object->getDescription()),
      id(new PhorgeRemarkupEditField())
        ->setKey('risks')
        ->setLabel(pht('Risks/Challenges'))
        ->setDescription(pht('Initiative risks and challenges.'))
        ->setConduitTypeDescription(pht('Initiative risks and challenges.'))
        ->setTransactionType(
          FundInitiativeRisksTransaction::TRANSACTIONTYPE)
        ->setValue($object->getRisks()),

      );

  }

}
