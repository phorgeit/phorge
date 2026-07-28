<?php

abstract class PhameBlogTransactionType
  extends PhabricatorModularTransactionType {

  protected function renderObjectType() {
    return 'blog';
  }

}
