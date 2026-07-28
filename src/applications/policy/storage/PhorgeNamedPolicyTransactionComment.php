<?php

final class PhorgeNamedPolicyTransactionComment
  extends PhabricatorApplicationTransactionComment {

  public function getApplicationTransactionObject() {
    return new PhorgeNamedPolicyTransaction();
  }

}
