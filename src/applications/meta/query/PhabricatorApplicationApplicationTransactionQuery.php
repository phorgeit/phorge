<?php

final class PhabricatorApplicationApplicationTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhabricatorApplicationApplicationTransaction();
  }

  // NOTE: Although this belongs to the "Applications" application, trying
  // to filter its results just leaves us recursing indefinitely. Users
  // always have access to applications regardless of other policy settings
  // anyway.
  public function getQueryApplicationClass() {
    return null;
  }
}
