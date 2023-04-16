<?php

final class FundInitiativeFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'fund';
  }

  public function getScopeName() {
    return 'initiative';
  }

  public function newSearchEngine() {
    return new FundInitiativeSearchEngine();
  }

}
