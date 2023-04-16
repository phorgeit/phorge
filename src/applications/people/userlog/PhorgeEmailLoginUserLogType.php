<?php

final class PhorgeEmailLoginUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'email-login';

  public function getLogTypeName() {
    return pht('Email: Recovery Link');
  }

}
