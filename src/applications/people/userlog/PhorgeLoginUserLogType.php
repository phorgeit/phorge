<?php

final class PhorgeLoginUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'login';

  public function getLogTypeName() {
    return pht('Login');
  }

}
