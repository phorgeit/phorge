<?php

final class PhorgePasteFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'paste';
  }

  public function getScopeName() {
    return 'paste';
  }

  public function newSearchEngine() {
    return new PhorgePasteSearchEngine();
  }

}
