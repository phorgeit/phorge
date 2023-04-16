<?php

final class PhorgeWorkerDestructionEngineExtension
  extends PhorgeDestructionEngineExtension {

  const EXTENSIONKEY = 'workers';

  public function getExtensionName() {
    return pht('Worker Tasks');
  }

  public function destroyObject(
    PhorgeDestructionEngine $engine,
    $object) {

    $tasks = id(new PhorgeWorkerActiveTask())->loadAllWhere(
      'objectPHID = %s',
      $object->getPHID());

    foreach ($tasks as $task) {
      $task->archiveTask(
        PhorgeWorkerArchiveTask::RESULT_CANCELLED,
        0);
    }
  }

}
