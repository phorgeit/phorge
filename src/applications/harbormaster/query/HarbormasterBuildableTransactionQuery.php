<?php

final class HarbormasterBuildableTransactionQuery
  extends PhorgeApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HarbormasterBuildableTransaction();
  }

}
