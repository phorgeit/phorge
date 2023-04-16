<?php

final class PhorgeLoginFailureUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'login-fail';

  public function getLogTypeName() {
    return pht('Login: Failure');
  }

}
