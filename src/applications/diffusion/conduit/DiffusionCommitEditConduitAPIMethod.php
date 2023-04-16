<?php

final class DiffusionCommitEditConduitAPIMethod
  extends PhorgeEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'diffusion.commit.edit';
  }

  public function newEditEngine() {
    return new DiffusionCommitEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to edit an existing commit. This method can not '.
      'create new commits.');
  }

}
