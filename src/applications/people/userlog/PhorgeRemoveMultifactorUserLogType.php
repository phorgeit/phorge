<?php

final class PhorgeRemoveMultifactorUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'multi-remove';

  public function getLogTypeName() {
    return pht('Multi-Factor: Remove Factor');
  }

}
