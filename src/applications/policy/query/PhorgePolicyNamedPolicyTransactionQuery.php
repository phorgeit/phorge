<?php

final class PhorgePolicyNamedPolicyTransactionQuery
  extends PhabricatorApplicationTransactionQuery {

  public function getTemplateApplicationTransaction() {
    return new PhorgeNamedPolicyTransaction();
  }

}
