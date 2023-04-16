<?php

final class ManiphestTaskFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'maniphest';
  }

  public function getScopeName() {
    return 'task';
  }

  public function newSearchEngine() {
    return new ManiphestTaskSearchEngine();
  }

}
