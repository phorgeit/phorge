<?php

final class DiffusionCommitFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'repository';
  }

  public function getScopeName() {
    return 'commit';
  }

  public function newSearchEngine() {
    return new PhorgeCommitSearchEngine();
  }

}
