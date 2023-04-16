<?php

final class PhorgePackagesVersionEditEngine
  extends PhorgePackagesEditEngine {

  const ENGINECONST = 'packages.version';

  public function getEngineName() {
    return pht('Package Versions');
  }

  public function getSummaryHeader() {
    return pht('Edit Package Version Configurations');
  }

  public function getSummaryText() {
    return pht('This engine is used to edit Packages versions.');
  }

  protected function newEditableObject() {
    $viewer = $this->getViewer();
    return PhorgePackagesVersion::initializeNewVersion($viewer);
  }

  protected function newObjectQuery() {
    return new PhorgePackagesVersionQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Version');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Version');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Version: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return pht('Edit Version');
  }

  protected function getObjectCreateShortText() {
    return pht('Create Version');
  }

  protected function getObjectName() {
    return pht('Version');
  }

  protected function getEditorURI() {
    return '/packages/version/edit/';
  }

  protected function getObjectCreateCancelURI($object) {
    return '/packages/version/';
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function buildCustomEditFields($object) {
    $fields = array();

    if ($this->getIsCreate()) {
      $fields[] = id(new PhorgeDatasourceEditField())
        ->setKey('package')
        ->setAliases(array('packagePHID'))
        ->setLabel(pht('Package'))
        ->setDescription(pht('Package for this version.'))
        ->setTransactionType(
          PhorgePackagesVersionPackageTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setDatasource(new PhorgePackagesPackageDatasource())
        ->setSingleValue($object->getPackagePHID());

      $fields[] = id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Name of the version.'))
        ->setTransactionType(
          PhorgePackagesVersionNameTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setValue($object->getName());
    }

    return $fields;
  }

}
