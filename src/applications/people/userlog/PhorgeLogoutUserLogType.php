<?php

final class PhorgeLogoutUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'logout';

  public function getLogTypeName() {
    return pht('Logout');
  }

}
