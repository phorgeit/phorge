<?php

final class PhameBlogFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'phame';
  }

  public function getScopeName() {
    return 'blog';
  }

  public function newSearchEngine() {
    return new PhameBlogSearchEngine();
  }

}
