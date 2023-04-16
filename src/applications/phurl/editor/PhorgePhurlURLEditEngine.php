<?php

final class PhorgePhurlURLEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'phurl.url';

  public function getEngineName() {
    return pht('Phurl');
  }

  public function getEngineApplicationClass() {
    return 'PhorgePhurlApplication';
  }

  public function getSummaryHeader() {
    return pht('Configure Phurl Forms');
  }

  public function getSummaryText() {
    return pht('Configure creation and editing forms in Phurl.');
  }

  public function isEngineConfigurable() {
    return false;
  }

  protected function newEditableObject() {
    return PhorgePhurlURL::initializeNewPhurlURL($this->getViewer());
  }

  protected function newObjectQuery() {
    return new PhorgePhurlURLQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New URL');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit URL: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return $object->getName();
  }

  protected function getObjectCreateShortText() {
    return pht('Create URL');
  }

  protected function getObjectName() {
    return pht('URL');
  }

  protected function getObjectCreateCancelURI($object) {
    return $this->getApplication()->getApplicationURI('/');
  }

  protected function getEditorURI() {
    return $this->getApplication()->getApplicationURI('url/edit/');
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function getCreateNewObjectPolicy() {
    return $this->getApplication()->getPolicy(
      PhorgePhurlURLCreateCapability::CAPABILITY);
  }

  protected function buildCustomEditFields($object) {

    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('URL name.'))
        ->setIsRequired(true)
        ->setConduitTypeDescription(pht('New URL name.'))
        ->setTransactionType(
          PhorgePhurlURLNameTransaction::TRANSACTIONTYPE)
        ->setValue($object->getName()),
      id(new PhorgeTextEditField())
        ->setKey('url')
        ->setLabel(pht('URL'))
        ->setDescription(pht('The URL to shorten.'))
        ->setConduitTypeDescription(pht('New URL.'))
        ->setValue($object->getLongURL())
        ->setIsRequired(true)
        ->setTransactionType(
          PhorgePhurlURLLongURLTransaction::TRANSACTIONTYPE),
      id(new PhorgeTextEditField())
        ->setKey('alias')
        ->setLabel(pht('Alias'))
        ->setIsRequired(true)
        ->setTransactionType(
          PhorgePhurlURLAliasTransaction::TRANSACTIONTYPE)
        ->setDescription(pht('The alias to give the URL.'))
        ->setConduitTypeDescription(pht('New alias.'))
        ->setValue($object->getAlias()),
      id(new PhorgeRemarkupEditField())
        ->setKey('description')
        ->setLabel(pht('Description'))
        ->setDescription(pht('URL long description.'))
        ->setConduitTypeDescription(pht('New URL description.'))
        ->setTransactionType(
          PhorgePhurlURLDescriptionTransaction::TRANSACTIONTYPE)
        ->setValue($object->getDescription()),
    );
  }

}
