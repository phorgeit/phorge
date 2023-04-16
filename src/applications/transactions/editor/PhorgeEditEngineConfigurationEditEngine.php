<?php

final class PhorgeEditEngineConfigurationEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'transactions.editengine.config';

  private $targetEngine;

  public function setTargetEngine(PhorgeEditEngine $target_engine) {
    $this->targetEngine = $target_engine;
    return $this;
  }

  public function getTargetEngine() {
    if (!$this->targetEngine) {
      // If we don't have a target engine, assume we're editing ourselves.
      return new PhorgeEditEngineConfigurationEditEngine();
    }
    return $this->targetEngine;
  }

  protected function getCreateNewObjectPolicy() {
    return $this->getTargetEngine()
      ->getApplication()
      ->getPolicy(PhorgePolicyCapability::CAN_EDIT);
  }

  public function getEngineName() {
    return pht('Edit Configurations');
  }

  public function getSummaryHeader() {
    return pht('Configure Forms for Configuring Forms');
  }

  public function getSummaryText() {
    return pht(
      'Change how forms in other applications are created and edited. '.
      'Advanced!');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeTransactionsApplication';
  }

  protected function newEditableObject() {
    return PhorgeEditEngineConfiguration::initializeNewConfiguration(
      $this->getViewer(),
      $this->getTargetEngine());
  }

  protected function newObjectQuery() {
    return id(new PhorgeEditEngineConfigurationQuery());
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New Form');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Form %d: %s', $object->getID(), $object->getDisplayName());
  }

  protected function getObjectEditShortText($object) {
    return pht('Form %d', $object->getID());
  }

  protected function getObjectCreateShortText() {
    return pht('Create Form');
  }

  protected function getObjectName() {
    return pht('Form');
  }

  protected function getObjectViewURI($object) {
    $id = $object->getID();
    return $this->getURI("view/{$id}/");
  }

  protected function getEditorURI() {
    return $this->getURI('edit/');
  }

  protected function getObjectCreateCancelURI($object) {
    return $this->getURI();
  }

  private function getURI($path = null) {
    $engine_key = $this->getTargetEngine()->getEngineKey();
    return "/transactions/editengine/{$engine_key}/{$path}";
  }

  protected function buildCustomEditFields($object) {
    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Name of the form.'))
        ->setTransactionType(
          PhorgeEditEngineNameTransaction::TRANSACTIONTYPE)
        ->setValue($object->getName()),
      id(new PhorgeRemarkupEditField())
        ->setKey('preamble')
        ->setLabel(pht('Preamble'))
        ->setDescription(pht('Optional instructions, shown above the form.'))
        ->setTransactionType(
          PhorgeEditEnginePreambleTransaction::TRANSACTIONTYPE)
        ->setValue($object->getPreamble()),
    );
  }

}
