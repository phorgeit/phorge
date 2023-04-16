<?php

final class DiffusionRepositorySearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'diffusion.repository.search';
  }

  public function newSearchEngine() {
    return new PhorgeRepositorySearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about repositories.');
  }

}
