<?php

final class PhorgeFullLoginUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'login-full';

  public function getLogTypeName() {
    return pht('Login: Upgrade to Full');
  }

}
