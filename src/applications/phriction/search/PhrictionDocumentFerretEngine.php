<?php

final class PhrictionDocumentFerretEngine
  extends PhorgeFerretEngine {

  public function getApplicationName() {
    return 'phriction';
  }

  public function getScopeName() {
    return 'document';
  }

  public function newSearchEngine() {
    return new PhrictionDocumentSearchEngine();
  }

}
