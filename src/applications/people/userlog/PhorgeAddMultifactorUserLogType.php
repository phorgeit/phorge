<?php

final class PhorgeAddMultifactorUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'multi-add';

  public function getLogTypeName() {
    return pht('Multi-Factor: Add Factor');
  }

}
