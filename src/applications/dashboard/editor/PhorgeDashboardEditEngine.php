<?php

final class PhorgeDashboardEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'dashboard';

  public function isEngineConfigurable() {
    return false;
  }

  public function getEngineName() {
    return pht('Dashboards');
  }

  public function getSummaryHeader() {
    return pht('Edit Dashboards');
  }

  public function getSummaryText() {
    return pht('This engine is used to modify dashboards.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  protected function newEditableObject() {
    $viewer = $this->getViewer();
    return PhorgeDashboard::initializeNewDashboard($viewer);
  }

  protected function newObjectQuery() {
    return new PhorgeDashboardQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Dashboard');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Dashboard');
  }

  protected function getObjectCreateCancelURI($object) {
    return '/dashboard/';
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Dashboard: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return pht('Edit Dashboard');
  }

  protected function getObjectCreateShortText() {
    return pht('Create Dashboard');
  }

  protected function getObjectName() {
    return pht('Dashboard');
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function buildCustomEditFields($object) {
    $layout_options = PhorgeDashboardLayoutMode::getLayoutModeMap();

    $fields = array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Name of the dashboard.'))
        ->setConduitDescription(pht('Rename the dashboard.'))
        ->setConduitTypeDescription(pht('New dashboard name.'))
        ->setTransactionType(
          PhorgeDashboardNameTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setValue($object->getName()),
      id(new PhorgeIconSetEditField())
        ->setKey('icon')
        ->setLabel(pht('Icon'))
        ->setTransactionType(
            PhorgeDashboardIconTransaction::TRANSACTIONTYPE)
        ->setIconSet(new PhorgeDashboardIconSet())
        ->setDescription(pht('Dashboard icon.'))
        ->setConduitDescription(pht('Change the dashboard icon.'))
        ->setConduitTypeDescription(pht('New dashboard icon.'))
        ->setValue($object->getIcon()),
      id(new PhorgeSelectEditField())
        ->setKey('layout')
        ->setLabel(pht('Layout'))
        ->setDescription(pht('Dashboard layout mode.'))
        ->setConduitDescription(pht('Change the dashboard layout mode.'))
        ->setConduitTypeDescription(pht('New dashboard layout mode.'))
        ->setTransactionType(
          PhorgeDashboardLayoutTransaction::TRANSACTIONTYPE)
        ->setOptions($layout_options)
        ->setValue($object->getRawLayoutMode()),
    );

    return $fields;
  }

}
