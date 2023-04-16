<?php

final class PhorgeRebuildIndexesWorker extends PhorgeWorker {

  public static function rebuildObjectsWithQuery($query_class) {
    parent::scheduleTask(
      __CLASS__,
      array(
        'queryClass' => $query_class,
      ),
      array(
        'priority' => parent::PRIORITY_INDEX,
      ));
  }

  protected function doWork() {
    $viewer = PhorgeUser::getOmnipotentUser();

    $data = $this->getTaskData();
    $query_class = idx($data, 'queryClass');

    try {
      $query = newv($query_class, array());
    } catch (Exception $ex) {
      throw new PhorgeWorkerPermanentFailureException(
        pht(
          'Unable to instantiate query class "%s": %s',
           $query_class,
           $ex->getMessage()));
    }

    $query->setViewer($viewer);

    $iterator = new PhorgeQueryIterator($query);
    foreach ($iterator as $object) {
      PhorgeSearchWorker::queueDocumentForIndexing(
        $object->getPHID(),
        array(
          'force' => true,
        ));
    }
  }

}
