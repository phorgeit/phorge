<?php

final class PhorgeCalendarICSURIImportEngine
  extends PhorgeCalendarICSImportEngine {

  const ENGINETYPE = 'icsuri';

  public function getImportEngineName() {
    return pht('Import .ics URI');
  }

  public function getImportEngineTypeName() {
    return pht('.ics URI');
  }

  public function getImportEngineHint() {
    return pht('Import or subscribe to a calendar in .ics format by URI.');
  }

  public function supportsTriggers(PhorgeCalendarImport $import) {
    return true;
  }

  public function appendImportProperties(
    PhorgeUser $viewer,
    PhorgeCalendarImport $import,
    PHUIPropertyListView $properties) {

    $uri_key = PhorgeCalendarImportICSURITransaction::PARAMKEY_URI;
    $uri = $import->getParameter($uri_key);

    // Since the URI may contain a secret hash, don't show it to users who
    // can not edit the import.
    $can_edit = PhorgePolicyFilter::hasCapability(
      $viewer,
      $import,
      PhorgePolicyCapability::CAN_EDIT);
    if (!$can_edit) {
      $uri_display = phutil_tag('em', array(), pht('Restricted'));
    } else if (!PhorgeEnv::isValidRemoteURIForLink($uri)) {
      $uri_display = $uri;
    } else {
      $uri_display = phutil_tag(
        'a',
        array(
          'href' => $uri,
          'target' => '_blank',
          'rel' => 'noreferrer',
        ),
        $uri);
    }

    $properties->addProperty(pht('Source URI'), $uri_display);
  }

  public function newEditEngineFields(
    PhorgeEditEngine $engine,
    PhorgeCalendarImport $import) {
    $fields = array();

    if ($engine->getIsCreate()) {
      $fields[] = id(new PhorgeTextEditField())
        ->setKey('uri')
        ->setLabel(pht('URI'))
        ->setDescription(pht('URI to import.'))
        ->setTransactionType(
          PhorgeCalendarImportICSURITransaction::TRANSACTIONTYPE)
        ->setConduitDescription(pht('URI to import.'))
        ->setConduitTypeDescription(pht('New URI.'));
    }

    return $fields;
  }

  public function getDisplayName(PhorgeCalendarImport $import) {
    return pht('ICS URI');
  }

  public function importEventsFromSource(
    PhorgeUser $viewer,
    PhorgeCalendarImport $import,
    $should_queue) {

    $uri_key = PhorgeCalendarImportICSURITransaction::PARAMKEY_URI;
    $uri = $import->getParameter($uri_key);

    PhorgeSystemActionEngine::willTakeAction(
      array($viewer->getPHID()),
      new PhorgeFilesOutboundRequestAction(),
      1);

    $file = PhorgeFile::newFromFileDownload(
      $uri,
      array(
        'viewPolicy' => PhorgePolicies::POLICY_NOONE,
        'authorPHID' => $import->getAuthorPHID(),
        'canCDN' => true,
      ));

    $import->newLogMessage(
      PhorgeCalendarImportFetchLogType::LOGTYPE,
      array(
        'file.phid' => $file->getPHID(),
      ));

    $data = $file->loadFileData();

    if ($should_queue && $this->shouldQueueDataImport($data)) {
      return $this->queueDataImport($import, $data);
    }

    return $this->importICSData($viewer, $import, $data);
  }

  public function canDisable(
    PhorgeUser $viewer,
    PhorgeCalendarImport $import) {
    return true;
  }

}
