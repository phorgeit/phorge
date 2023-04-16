<?php

final class DiffusionCommitSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'diffusion.commit.search';
  }

  public function newSearchEngine() {
    return new PhorgeCommitSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about commits.');
  }

}
