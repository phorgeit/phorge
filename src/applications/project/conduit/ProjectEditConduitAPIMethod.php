<?php

final class ProjectEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'project.edit';
  }

  public function newEditEngine() {
    return new PhorgeProjectEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to create a new project or edit an existing one.');
  }

}
