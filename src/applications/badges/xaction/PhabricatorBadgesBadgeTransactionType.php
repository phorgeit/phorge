<?php

abstract class PhabricatorBadgesBadgeTransactionType
  extends PhabricatorModularTransactionType {

  protected function renderObjectType() {
    return pht('badge');
  }

}
