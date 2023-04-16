<?php

final class PhorgeCalendarImportDeleteTransaction
  extends PhorgeCalendarImportTransactionType {

  const TRANSACTIONTYPE = 'calendar.import.delete';

  public function generateOldValue($object) {
    return false;
  }

  public function applyExternalEffects($object, $value) {
    $events = id(new PhorgeCalendarEventQuery())
      ->setViewer($this->getActor())
      ->withImportSourcePHIDs(array($object->getPHID()))
      ->execute();

    $engine = new PhorgeDestructionEngine();
    foreach ($events as $event) {
      $engine->destroyObject($event);
    }
  }

  public function getTitle() {
    return pht(
      '%s deleted imported events from this source.',
      $this->renderAuthor());
  }

}
