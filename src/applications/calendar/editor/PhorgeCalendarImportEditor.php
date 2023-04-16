<?php

final class PhorgeCalendarImportEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeCalendarApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Calendar Imports');
  }

  public function getCreateObjectTitle($author, $object) {
    return pht('%s created this import.', $author);
  }

  public function getTransactionTypes() {
    $types = parent::getTransactionTypes();

    $types[] = PhorgeTransactions::TYPE_VIEW_POLICY;
    $types[] = PhorgeTransactions::TYPE_EDIT_POLICY;

    return $types;
  }

  protected function applyFinalEffects(
    PhorgeLiskDAO $object,
    array $xactions) {
    $actor = $this->getActor();

    // We import events when you create a source, or if you later reload it
    // explicitly.
    $should_reload = $this->getIsNewObject();

    // We adjust the import trigger if you change the import frequency or
    // disable the import.
    $should_trigger = false;

    foreach ($xactions as $xaction) {
      $xaction_type = $xaction->getTransactionType();
      switch ($xaction_type) {
        case PhorgeCalendarImportReloadTransaction::TRANSACTIONTYPE:
          $should_reload = true;
          break;
        case PhorgeCalendarImportFrequencyTransaction::TRANSACTIONTYPE:
          $should_trigger = true;
          break;
        case PhorgeCalendarImportDisableTransaction::TRANSACTIONTYPE:
          $should_trigger = true;
          break;
      }
    }

    if ($should_reload) {
      $import_engine = $object->getEngine();
      $import_engine->importEventsFromSource($actor, $object, true);
    }

    if ($should_trigger) {
      $trigger_phid = $object->getTriggerPHID();
      if ($trigger_phid) {
        $trigger = id(new PhorgeWorkerTriggerQuery())
          ->setViewer($actor)
          ->withPHIDs(array($trigger_phid))
          ->executeOne();

        if ($trigger) {
          $engine = new PhorgeDestructionEngine();
          $engine->destroyObject($trigger);
        }
      }

      $frequency = $object->getTriggerFrequency();
      $now = PhorgeTime::getNow();
      switch ($frequency) {
        case PhorgeCalendarImport::FREQUENCY_ONCE:
          $clock = null;
          break;
        case PhorgeCalendarImport::FREQUENCY_HOURLY:
          $clock = new PhorgeMetronomicTriggerClock(
            array(
              'period' => phutil_units('1 hour in seconds'),
            ));
          break;
        case PhorgeCalendarImport::FREQUENCY_DAILY:
          $clock = new PhorgeDailyRoutineTriggerClock(
            array(
              'start' => $now,
            ));
          break;
        default:
          throw new Exception(
            pht(
              'Unknown import trigger frequency "%s".',
              $frequency));
      }

      // If the object has been disabled, don't write a new trigger.
      if ($object->getIsDisabled()) {
        $clock = null;
      }

      if ($clock) {
        $trigger_action = new PhorgeScheduleTaskTriggerAction(
          array(
            'class' => 'PhorgeCalendarImportReloadWorker',
            'data' => array(
              'importPHID' => $object->getPHID(),
              'via' => PhorgeCalendarImportReloadWorker::VIA_TRIGGER,
            ),
            'options' => array(
              'objectPHID' => $object->getPHID(),
              'priority' => PhorgeWorker::PRIORITY_BULK,
            ),
          ));

        $trigger_phid = PhorgePHID::generateNewPHID(
          PhorgeWorkerTriggerPHIDType::TYPECONST);

        $object
          ->setTriggerPHID($trigger_phid)
          ->save();

        $trigger = id(new PhorgeWorkerTrigger())
          ->setClock($clock)
          ->setAction($trigger_action)
          ->setPHID($trigger_phid)
          ->save();
      } else {
        $object
          ->setTriggerPHID(null)
          ->save();
      }
    }

    return $xactions;
  }


}
