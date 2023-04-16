<?php

final class FileDeletionWorker extends PhorgeWorker {

  private function loadFile() {
    $phid = idx($this->getTaskData(), 'objectPHID');
    if (!$phid) {
      throw new PhorgeWorkerPermanentFailureException(
        pht('No "%s" in task data.', 'objectPHID'));
    }

    $file = id(new PhorgeFileQuery())
      ->setViewer(PhorgeUser::getOmnipotentUser())
      ->withPHIDs(array($phid))
      ->executeOne();

    if (!$file) {
      throw new PhorgeWorkerPermanentFailureException(
        pht('File "%s" does not exist.', $phid));
    }

    return $file;
  }

  protected function doWork() {
    $file = $this->loadFile();
    $engine = new PhorgeDestructionEngine();
    $engine->destroyObject($file);
  }

}
