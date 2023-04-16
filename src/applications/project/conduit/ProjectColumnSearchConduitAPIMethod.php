<?php

final class ProjectColumnSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'project.column.search';
  }

  public function newSearchEngine() {
    return new PhorgeProjectColumnSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about workboard columns.');
  }

}
