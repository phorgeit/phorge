<?php

final class PhorgeResetPasswordUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'reset-pass';

  public function getLogTypeName() {
    return pht('Reset Password');
  }

}
