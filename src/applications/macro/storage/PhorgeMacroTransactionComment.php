<?php

final class PhorgeMacroTransactionComment
  extends PhorgeApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhorgeMacroTransaction();
  }

}
