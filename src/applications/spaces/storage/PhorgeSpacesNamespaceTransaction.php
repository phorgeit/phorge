<?php

final class PhorgeSpacesNamespaceTransaction
  extends PhorgeModularTransaction {

  public function getApplicationName() {
    return 'spaces';
  }

  public function getApplicationTransactionType() {
    return PhorgeSpacesNamespacePHIDType::TYPECONST;
  }

  public function getBaseTransactionClass() {
    return 'PhorgeSpacesNamespaceTransactionType';
  }

}
