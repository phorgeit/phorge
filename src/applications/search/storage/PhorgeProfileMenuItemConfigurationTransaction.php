<?php

final class PhorgeProfileMenuItemConfigurationTransaction
  extends PhorgeApplicationTransaction {

  const TYPE_PROPERTY = 'profilepanel.property';
  const TYPE_ORDER = 'profilepanel.order';
  const TYPE_VISIBILITY = 'profilepanel.visibility';

  public function getApplicationName() {
    return 'search';
  }

  public function getTableName() {
    // At least for now, this object uses an older table name.
    return 'search_profilepanelconfigurationtransaction';
  }

  public function getApplicationTransactionType() {
    return PhorgeProfileMenuItemPHIDType::TYPECONST;
  }

}
