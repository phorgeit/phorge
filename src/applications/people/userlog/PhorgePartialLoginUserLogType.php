<?php

final class PhorgePartialLoginUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'login-partial';

  public function getLogTypeName() {
    return pht('Login: Partial Login');
  }

}
