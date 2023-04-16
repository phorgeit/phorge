<?php

final class PhamePostFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'phame';
  }

  public function getScopeName() {
    return 'post';
  }

  public function newSearchEngine() {
    return new PhamePostSearchEngine();
  }

}
