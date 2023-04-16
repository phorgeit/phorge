<?php

final class PhorgeUserPreferencesTransaction
  extends PhorgeApplicationTransaction {

  const TYPE_SETTING = 'setting';

  const PROPERTY_SETTING = 'setting.key';

  public function getApplicationName() {
    return 'user';
  }

  public function getApplicationTransactionType() {
    return PhorgeUserPreferencesPHIDType::TYPECONST;
  }

}
