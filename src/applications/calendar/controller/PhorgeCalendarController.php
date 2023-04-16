<?php

abstract class PhorgeCalendarController extends PhorgeController {

  protected function newICSResponse(
    PhorgeUser $viewer,
    $file_name,
    array $events) {

    $ics_data = id(new PhorgeCalendarICSWriter())
      ->setViewer($viewer)
      ->setEvents($events)
      ->writeICSDocument();

    return id(new AphrontFileResponse())
      ->setDownload($file_name)
      ->setMimeType('text/calendar')
      ->setContent($ics_data);
  }

  protected function newImportedEventResponse(PhorgeCalendarEvent $event) {
    if (!$event->isImportedEvent()) {
      return null;
    }

    // Give the user a specific, detailed message if they try to edit an
    // imported event via common web paths. Other edits (including those via
    // the API) are blocked by the normal policy system, but this makes it more
    // clear exactly why the event can't be edited.

    return $this->newDialog()
      ->setTitle(pht('Can Not Edit Imported Event'))
      ->appendParagraph(
        pht(
          'This event has been imported from an external source and '.
          'can not be edited.'))
      ->addCancelButton($event->getURI(), pht('Done'));
  }

}
