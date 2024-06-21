<?php

final class HarbormasterBuildTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new HarbormasterBuildTransaction();
  }

  public function getQueryApplicationClass() {
    return PhabricatorHarbormasterApplication::class;
  }

}
