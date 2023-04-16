<?php

final class PhorgeMacroEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'macro.image';

  public function getEngineName() {
    return pht('Macro Image');
  }

  public function getSummaryHeader() {
    return pht('Configure Macro Image Forms');
  }

  public function getSummaryText() {
    return pht('Configure creation and editing of Macro images.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeMacroApplication';
  }

  public function isEngineConfigurable() {
    return false;
  }

  protected function newEditableObject() {
    $viewer = $this->getViewer();
    return PhorgeFileImageMacro::initializeNewFileImageMacro($viewer);
  }

  protected function newObjectQuery() {
    return new PhorgeMacroQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New Macro');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Macro %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return $object->getName();
  }

  protected function getObjectCreateShortText() {
    return pht('Create Macro');
  }

  protected function getObjectName() {
    return pht('Macro');
  }

  protected function getObjectViewURI($object) {
    return $object->getViewURI();
  }

  protected function getEditorURI() {
    return $this->getApplication()->getApplicationURI('edit/');
  }

  protected function getCreateNewObjectPolicy() {
    return $this->getApplication()->getPolicy(
      PhorgeMacroManageCapability::CAPABILITY);
  }

  protected function willConfigureFields($object, array $fields) {
    if ($this->getIsCreate()) {
      $subscribers_field = idx($fields,
        PhorgeSubscriptionsEditEngineExtension::FIELDKEY);
      if ($subscribers_field) {
        // By default, hide the subscribers field when creating a macro
        // because it makes the workflow SO HARD and wastes SO MUCH TIME.
        $subscribers_field->setIsHidden(true);
      }
    }
    return $fields;
  }

  protected function buildCustomEditFields($object) {

    return array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Macro name.'))
        ->setConduitDescription(pht('Name of the macro.'))
        ->setConduitTypeDescription(pht('New macro name.'))
        ->setTransactionType(PhorgeMacroNameTransaction::TRANSACTIONTYPE)
        ->setIsRequired(true)
        ->setValue($object->getName()),
      id(new PhorgeFileEditField())
        ->setKey('filePHID')
        ->setLabel(pht('Image File'))
        ->setDescription(pht('Image file to import.'))
        ->setTransactionType(PhorgeMacroFileTransaction::TRANSACTIONTYPE)
        ->setConduitDescription(pht('File PHID to import.'))
        ->setConduitTypeDescription(pht('File PHID.'))
        ->setValue($object->getFilePHID()),
    );

  }

}
