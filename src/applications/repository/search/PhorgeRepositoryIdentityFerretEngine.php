<?php

final class PhorgeRepositoryIdentityFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'repository';
  }

  public function getScopeName() {
    return 'identity';
  }

  public function newSearchEngine() {
    return new DiffusionRepositoryIdentitySearchEngine();
  }

}
