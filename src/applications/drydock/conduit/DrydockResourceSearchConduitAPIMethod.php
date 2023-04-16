<?php

final class DrydockResourceSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'drydock.resource.search';
  }

  public function newSearchEngine() {
    return new DrydockResourceSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Retrieve information about Drydock resources.');
  }

}
