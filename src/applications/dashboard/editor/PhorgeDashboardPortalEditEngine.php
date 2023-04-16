<?php

final class PhorgeDashboardPortalEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'portal';

  public function isEngineConfigurable() {
    return false;
  }

  public function getEngineName() {
    return pht('Portals');
  }

  public function getSummaryHeader() {
    return pht('Edit Portals');
  }

  public function getSummaryText() {
    return pht('This engine is used to modify portals.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeDashboardApplication';
  }

  protected function newEditableObject() {
    return PhorgeDashboardPortal::initializeNewPortal();
  }

  protected function newObjectQuery() {
    return new PhorgeDashboardPortalQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create Portal');
  }

  protected function getObjectCreateButtonText($object) {
    return pht('Create Portal');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Portal: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return pht('Edit Portal');
  }

  protected function getObjectCreateShortText() {
    return pht('Create Portal');
  }

  protected function getObjectName() {
    return pht('Portal');
  }

  protected function getObjectViewURI($object) {
    if ($this->getIsCreate()) {
      return $object->getURI();
    } else {
      return '/portal/view/'.$object->getID().'/view/manage/';
    }
  }

  protected function getEditorURI() {
    return '/portal/edit/';
  }

  protected function buildCustomEditFields($object) {
    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Name of the portal.'))
        ->setConduitDescription(pht('Rename the portal.'))
        ->setConduitTypeDescription(pht('New portal name.'))
        ->setTransactionType(
            PhorgeDashboardPortalNameTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setValue($object->getName()),
    );
  }

}
