<?php

final class LegalpadDocumentSearchConduitAPIMethod
  extends PhabricatorSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'legalpad.document.search';
  }

  public function newSearchEngine() {
    return new LegalpadDocumentSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about legalpad documents.');
  }

}
