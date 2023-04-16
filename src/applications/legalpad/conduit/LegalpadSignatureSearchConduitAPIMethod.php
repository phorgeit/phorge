<?php

final class LegalpadSignatureSearchConduitAPIMethod
  extends PhorgeSearchEngineAPIMethod {

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
