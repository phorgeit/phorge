<?php

final class PhorgeSpacesNamespaceTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeSpacesNamespaceTransaction();
  }

}
