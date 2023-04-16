<?php

final class PhorgeMacroTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeMacroTransaction();
  }

}
