<?php

final class FundBackerEditor
  extends PhabricatorApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return PhabricatorFundApplication::class;
  }

  public function getEditorObjectsDescription() {
    return pht('Fund Backing');
  }

}
