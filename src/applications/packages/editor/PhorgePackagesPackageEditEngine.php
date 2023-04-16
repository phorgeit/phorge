<?php

final class PhorgePackagesPackageEditEngine
  extends PhorgePackagesEditEngine {

  const ENGINECONST = 'packages.package';

  public function getEngineName() {
    return pht('Package Packages');
  }

  public function getSummaryHeader() {
    return pht('Edit Package Package Configurations');
  }

  public function getSummaryText() {
    return pht('This engine is used to edit Packages packages.');
  }

  protected function newEditableObject() {
    $viewer = $this->getViewer();
    return PhorgePackagesPackage::initializeNewPackage($viewer);
  }

  protected function newObjectQuery() {
    return new PhorgePackagesPackageQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Package');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Package');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Package: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return pht('Edit Package');
  }

  protected function getObjectCreateShortText() {
    return pht('Create Package');
  }

  protected function getObjectName() {
    return pht('Package');
  }

  protected function getEditorURI() {
    return '/packages/package/edit/';
  }

  protected function getObjectCreateCancelURI($object) {
    return '/packages/package/';
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function buildCustomEditFields($object) {
    $fields = array();

    if ($this->getIsCreate()) {
      $fields[] = id(new PhorgeDatasourceEditField())
        ->setKey('publisher')
        ->setAliases(array('publisherPHID'))
        ->setLabel(pht('Publisher'))
        ->setDescription(pht('Publisher for this package.'))
        ->setTransactionType(
          PhorgePackagesPackagePublisherTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setDatasource(new PhorgePackagesPublisherDatasource())
        ->setSingleValue($object->getPublisherPHID());
    }

    $fields[] = id(new PhorgeTextEditField())
      ->setKey('name')
      ->setLabel(pht('Name'))
      ->setDescription(pht('Name of the package.'))
      ->setTransactionType(
        PhorgePackagesPackageNameTransaction::TRANSACTIONTYPE)
      ->setIsRequired(true)
      ->setValue($object->getName());

    if ($this->getIsCreate()) {
      $fields[] = id(new PhorgeTextEditField())
        ->setKey('packageKey')
        ->setLabel(pht('Package Key'))
        ->setDescription(pht('Unique key to identify the package.'))
        ->setTransactionType(
          PhorgePackagesPackageKeyTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setValue($object->getPackageKey());
    }

    return $fields;
  }

}
