<?php

final class FundBackerEditor
  extends PhorgeApplicationTransactionEditor {

  public function getEditorApplicationClass() {
    return 'PhorgeFundApplication';
  }

  public function getEditorObjectsDescription() {
    return pht('Fund Backing');
  }

}
