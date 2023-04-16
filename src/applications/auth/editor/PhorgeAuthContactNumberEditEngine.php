<?php

final class PhorgeAuthContactNumberEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'auth.contact';

  public function isEngineConfigurable() {
    return false;
  }

  public function getEngineName() {
    return pht('Contact Numbers');
  }

  public function getSummaryHeader() {
    return pht('Edit Contact Numbers');
  }

  public function getSummaryText() {
    return pht('This engine is used to edit contact numbers.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeAuthApplication';
  }

  protected function newEditableObject() {
    $viewer = $this->getViewer();
    return PhorgeAuthContactNumber::initializeNewContactNumber($viewer);
  }

  protected function newObjectQuery() {
    return new PhorgeAuthContactNumberQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Contact Number');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Contact Number');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Contact Number');
  }

  protected function getObjectEditShortText($object) {
    return $object->getObjectName();
  }

  protected function getObjectCreateShortText() {
    return pht('Create Contact Number');
  }

  protected function getObjectName() {
    return pht('Contact Number');
  }

  protected function getEditorURI() {
    return '/auth/contact/edit/';
  }

  protected function getObjectCreateCancelURI($object) {
    return '/settings/panel/contact/';
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function buildCustomEditFields($object) {
    return array(
      id(new PhorgeTextEditField())
        ->setKey('contactNumber')
        ->setTransactionType(
          PhorgeAuthContactNumberNumberTransaction::TRANSACTIONTYPE)
        ->setLabel(pht('Contact Number'))
        ->setDescription(pht('The contact number.'))
        ->setValue($object->getContactNumber())
        ->setIsRequired(true),
    );
  }

}
