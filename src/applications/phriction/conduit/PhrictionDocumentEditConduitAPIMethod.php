<?php

final class PhrictionDocumentEditConduitAPIMethod
  extends PhabricatorEditEngineAPIMethod {

  public function getAPIMethodName() {
    return 'phriction.document.edit';
  }

  public function newEditEngine() {
    return new PhrictionDocumentEditEngine();
  }

  public function getMethodSummary() {
    return pht(
      'Apply transactions to edit an existing phriction document.');
  }

}
