<?php

final class HarbormasterBuildStepSearchAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'harbormaster.step.search';
  }

  public function newSearchEngine() {
    return new HarbormasterBuildStepSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Retrieve information about Harbormaster build steps.');
  }

}
