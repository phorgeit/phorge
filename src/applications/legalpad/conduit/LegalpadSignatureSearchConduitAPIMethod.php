<?php

final class LegalpadSignatureSearchConduitAPIMethod
  extends PhabricatorSearchEngineAPIMethod {

  public function getAPIMethodName() {
    return 'legalpad.signature.search';
  }

  public function newSearchEngine() {
    return new LegalpadDocumentSignatureSearchEngine();
  }

  public function getMethodSummary() {
    return pht('Read information about legalpad document signatures.');
  }

}
