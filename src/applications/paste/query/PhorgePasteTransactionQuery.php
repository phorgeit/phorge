<?php

final class PhorgePasteTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgePasteTransaction();
  }

}
