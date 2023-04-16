<?php

final class PhorgeRepositoryIdentityEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'repository.identity';

  public function isEngineConfigurable() {
    return false;
  }

  public function getEngineName() {
    return pht('Repository Identities');
  }

  public function getSummaryHeader() {
    return pht('Edit Repository Identity Configurations');
  }

  public function getSummaryText() {
    return pht('This engine is used to edit Repository identities.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function newEditableObject() {
    return new PhorgeRepositoryIdentity();
  }

  protected function newObjectQuery() {
    return new PhorgeRepositoryIdentityQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Identity');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Identity');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Identity: %s', $object->getIdentityShortName());
  }

  protected function getObjectEditShortText($object) {
    return pht('Edit Identity');
  }

  protected function getObjectCreateShortText() {
    return pht('Create Identity');
  }

  protected function getObjectName() {
    return pht('Identity');
  }

  protected function getEditorURI() {
    return '/diffusion/identity/edit/';
  }

  protected function getObjectCreateCancelURI($object) {
    return '/diffusion/identity/';
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function getCreateNewObjectPolicy() {
    return PhorgePolicies::POLICY_USER;
  }

  protected function buildCustomEditFields($object) {
    return array(
      id(new DiffusionIdentityAssigneeEditField())
        ->setKey('manuallySetUserPHID')
        ->setLabel(pht('Assigned To'))
        ->setDescription(pht('Override this identity\'s assignment.'))
        ->setTransactionType(
          PhorgeRepositoryIdentityAssignTransaction::TRANSACTIONTYPE)
        ->setIsCopyable(true)
        ->setIsNullable(true)
        ->setSingleValue($object->getManuallySetUserPHID()),

    );
  }

}
