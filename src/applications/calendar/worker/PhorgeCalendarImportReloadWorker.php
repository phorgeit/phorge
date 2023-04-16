<?php

final class PhorgeCalendarImportReloadWorker extends PhorgeWorker {

  const VIA_TRIGGER = 'trigger';
  const VIA_BACKGROUND = 'background';

  protected function doWork() {
    $import = $this->loadImport();
    $viewer = PhorgeUser::getOmnipotentUser();

    if ($import->getIsDisabled()) {
      return;
    }

    $author = id(new PhorgePeopleQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($import->getAuthorPHID()))
      ->needUserSettings(true)
      ->executeOne();

    $import_engine = $import->getEngine();

    $data = $this->getTaskData();
    $import->newLogMessage(
      PhorgeCalendarImportTriggerLogType::LOGTYPE,
      array(
        'via' => idx($data, 'via', self::VIA_TRIGGER),
      ));

    $import_engine->importEventsFromSource($author, $import, false);
  }

  private function loadImport() {
    $viewer = PhorgeUser::getOmnipotentUser();

    $data = $this->getTaskData();
    $import_phid = idx($data, 'importPHID');

    $import = id(new PhorgeCalendarImportQuery())
      ->setViewer($viewer)
      ->withPHIDs(array($import_phid))
      ->executeOne();
    if (!$import) {
      throw new PhorgeWorkerPermanentFailureException(
        pht(
          'Failed to load import with PHID "%s".',
          $import_phid));
    }

    return $import;
  }

}
