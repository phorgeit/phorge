<?php

final class PhorgeMetaMTAApplicationEmailTransaction
  extends PhorgeApplicationTransaction {

  const KEY_CONFIG = 'appemail.config.key';

  const TYPE_ADDRESS = 'appemail.address';
  const TYPE_CONFIG = 'appemail.config';

  public function getApplicationName() {
    return 'metamta';
  }

  public function getApplicationTransactionType() {
    return PhorgeMetaMTAApplicationEmailPHIDType::TYPECONST;
  }

}
