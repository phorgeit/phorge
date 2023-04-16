<?php

abstract class PhorgeCalendarICSImportEngine
  extends PhorgeCalendarImportEngine {

  final protected function importICSData(
    PhorgeUser $viewer,
    PhorgeCalendarImport $import,
    $data) {

    $parser = new PhutilICSParser();

    try {
      $document = $parser->parseICSData($data);
    } catch (PhutilICSParserException $ex) {
      // TODO: In theory, it would be nice to store these in a fully abstract
      // form so they can be translated at display time. As-is, we'll store the
      // error messages in whatever language we were using when the parser
      // failure occurred.

      $import->newLogMessage(
        PhorgeCalendarImportICSLogType::LOGTYPE,
        array(
          'ics.code' => $ex->getParserFailureCode(),
          'ics.message' => $ex->getMessage(),
        ));

      $document = null;
    }

    foreach ($parser->getWarnings() as $warning) {
      $import->newLogMessage(
        PhorgeCalendarImportICSWarningLogType::LOGTYPE,
        array(
          'ics.warning.code' => $warning['code'],
          'ics.warning.line' => $warning['line'],
          'ics.warning.text' => $warning['text'],
          'ics.warning.message' => $warning['message'],
        ));
    }

    return $this->importEventDocument($viewer, $import, $document);
  }

}
