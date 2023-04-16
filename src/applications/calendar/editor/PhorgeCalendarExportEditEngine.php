<?php

final class PhorgeCalendarExportEditEngine
  extends PhorgeEditEngine {

  const ENGINECONST = 'calendar.export';

  public function getEngineName() {
    return pht('Calendar Exports');
  }

  public function isEngineConfigurable() {
    return false;
  }

  public function getSummaryHeader() {
    return pht('Configure Calendar Export Forms');
  }

  public function getSummaryText() {
    return pht('Configure how users create and edit exports.');
  }

  public function getEngineApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

  protected function newEditableObject() {
    return PhorgeCalendarExport::initializeNewCalendarExport(
      $this->getViewer());
  }

  protected function newObjectQuery() {
    return new PhorgeCalendarExportQuery();
  }

  protected function getObjectCreateTitleText($object) {
    return pht('Create New Export');
  }

  protected function getObjectEditTitleText($object) {
    return pht('Edit Export: %s', $object->getName());
  }

  protected function getObjectEditShortText($object) {
    return pht('Export %d', $object->getID());
  }

  protected function getObjectCreateShortText() {
    return pht('Create Export');
  }

  protected function getObjectName() {
    return pht('Export');
  }

  protected function getObjectViewURI($object) {
    return $object->getURI();
  }

  protected function getEditorURI() {
    return $this->getApplication()->getApplicationURI('export/edit/');
  }

  protected function buildCustomEditFields($object) {
    $viewer = $this->getViewer();

    $export_modes = PhorgeCalendarExport::getAvailablePolicyModes();
    $export_modes = array_fuse($export_modes);

    $current_mode = $object->getPolicyMode();
    if (empty($export_modes[$current_mode])) {
      array_unshift($export_modes, $current_mode);
    }

    $mode_options = array();
    foreach ($export_modes as $export_mode) {
      $mode_name = PhorgeCalendarExport::getPolicyModeName($export_mode);
      $mode_summary = PhorgeCalendarExport::getPolicyModeSummary(
        $export_mode);
      $mode_options[$export_mode] = pht('%s: %s', $mode_name, $mode_summary);
    }

    $fields = array(
      id(new PhorgeTextEditField())
        ->setKey('name')
        ->setLabel(pht('Name'))
        ->setDescription(pht('Name of the export.'))
        ->setIsRequired(true)
        ->setTransactionType(
          PhorgeCalendarExportNameTransaction::TRANSACTIONTYPE)
        ->setConduitDescription(pht('Rename the export.'))
        ->setConduitTypeDescription(pht('New export name.'))
        ->setValue($object->getName()),
      id(new PhorgeBoolEditField())
        ->setKey('disabled')
        ->setOptions(pht('Active'), pht('Disabled'))
        ->setLabel(pht('Disabled'))
        ->setDescription(pht('Disable the export.'))
        ->setTransactionType(
          PhorgeCalendarExportDisableTransaction::TRANSACTIONTYPE)
        ->setIsFormField(false)
        ->setConduitDescription(pht('Disable or restore the export.'))
        ->setConduitTypeDescription(pht('True to cancel the export.'))
        ->setValue($object->getIsDisabled()),
      id(new PhorgeTextEditField())
        ->setKey('queryKey')
        ->setLabel(pht('Query Key'))
        ->setDescription(pht('Query to execute.'))
        ->setIsRequired(true)
        ->setTransactionType(
          PhorgeCalendarExportQueryKeyTransaction::TRANSACTIONTYPE)
        ->setConduitDescription(pht('Change the export query key.'))
        ->setConduitTypeDescription(pht('New export query key.'))
        ->setValue($object->getQueryKey()),
      id(new PhorgeSelectEditField())
        ->setKey('mode')
        ->setLabel(pht('Mode'))
        ->setTransactionType(
          PhorgeCalendarExportModeTransaction::TRANSACTIONTYPE)
        ->setOptions($mode_options)
        ->setDescription(pht('Change the policy mode for the export.'))
        ->setConduitDescription(pht('Adjust export mode.'))
        ->setConduitTypeDescription(pht('New export mode.'))
        ->setValue($current_mode),

    );

    return $fields;
  }


}
