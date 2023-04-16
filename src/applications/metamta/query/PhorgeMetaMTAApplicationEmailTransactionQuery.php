<?php

final class PhorgeMetaMTAApplicationEmailTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeMetaMTAApplicationEmailTransaction();
  }

}
