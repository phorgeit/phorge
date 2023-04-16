<?php

final class PhorgeChangePasswordUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'change-password';

  public function getLogTypeName() {
    return pht('Change Password');
  }

}
