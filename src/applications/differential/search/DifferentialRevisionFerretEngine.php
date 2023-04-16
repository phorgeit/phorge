<?php

final class DifferentialRevisionFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'differential';
  }

  public function getScopeName() {
    return 'revision';
  }

  public function newSearchEngine() {
    return new DifferentialRevisionSearchEngine();
  }

}
