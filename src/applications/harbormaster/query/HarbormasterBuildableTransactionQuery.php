<?php

final class HarbormasterBuildableTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HarbormasterBuildableTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorHarbormasterApplication::class;
  }

}
