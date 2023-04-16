<?php

final class DifferentialChangesetSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'differential.changeset.search';
  }

  public function newSearchEngine() {
    return new DifferentialChangesetSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about changesets.');
  }

}
