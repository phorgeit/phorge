<?php

final class ProjectSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'project.search';
  }

  public function newSearchEngine() {
    return new PhorgeProjectSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about projects.');
  }

  protected function getCustomQueryMaps($query) {
    return array(
      'slugMap' => $query->getSlugMap(),
    );
  }

}
