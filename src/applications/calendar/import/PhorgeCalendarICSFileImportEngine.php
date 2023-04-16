<?php

final class PhorgeCalendarICSFileImportEngine
  extends PhorgeCalendarICSImportEngine {

  const ENGINETYPE = 'icsfile';

  public function getImportEngineName() {
    return pht('Import .ics File');
  }

  public function getImportEngineTypeName() {
    return pht('.ics File');
  }

  public function getImportEngineHint() {
    return pht('Import an event in ".ics" (iCalendar) format.');
  }

  public function supportsTriggers(PhorgeCalendarImport $import) {
    return false;
  }

  public function appendImportProperties(
    PhorgeUser $viewer,
    PhorgeCalendarImport $import,
    PHUIPropertyListView $properties) {

    $phid_key = PhorgeCalendarImportICSFileTransaction::PARAMKEY_FILE;
    $file_phid = $import->getParameter($phid_key);

    $properties->addProperty(
      pht('Source File'),
      $viewer->renderHandle($file_phid));
  }

  public function newEditEngineFields(
    PhorgeEditEngine $engine,
    PhorgeCalendarImport $import) {
    $fields = array();

    if ($engine->getIsCreate()) {
      $fields[] = id(new PhorgeFileEditField())
        ->setKey('icsFilePHID')
        ->setLabel(pht('ICS File'))
        ->setDescription(pht('ICS file to import.'))
        ->setTransactionType(
          PhorgeCalendarImportICSFileTransaction::TRANSACTIONTYPE)
        ->setConduitDescription(pht('File PHID to import.'))
        ->setConduitTypeDescription(pht('File PHID.'));
    }

    return $fields;
  }

  public function getDisplayName(PhorgeCalendarImport $import) {
    $filename_key = PhorgeCalendarImportICSFileTransaction::PARAMKEY_NAME;
    $filename = $import->getParameter($filename_key);
    if (strlen($filename)) {
      return pht('ICS File "%s"', $filename);
    } else {
      return pht('ICS File');
    }
  }

  public function importEventsFromSource(
    PhorgeUser $viewer,
    PhorgeCalendarImport $import,
    $should_queue) {

    $phid_key = PhorgeCalendarImportICSFileTransaction::PARAMKEY_FILE;
    $file_phid = $import->getParameter($phid_key);

    $file = id(new PhorgeFileQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($file_phid))
      ->executeOne();
    if (!$file) {
      throw new Exception(
        pht(
          'Unable to load file ("%s") for import.',
          $file_phid));
    }

    $data = $file->loadFileData();

    if ($should_queue && $this->shouldQueueDataImport($data)) {
      return $this->queueDataImport($import, $data);
    }

    return $this->importICSData($viewer, $import, $data);
  }

  public function canDisable(
    PhorgeUser $viewer,
    PhorgeCalendarImport $import) {
    return false;
  }

  public function explainCanDisable(
    PhorgeUser $viewer,
    PhorgeCalendarImport $import) {
    return pht(
      'You can not disable import of an ICS file because the entire import '.
      'occurs immediately when you upload the file. There is no further '.
      'activity to disable.');
  }


}
