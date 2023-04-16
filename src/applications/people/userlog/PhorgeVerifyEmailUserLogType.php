<?php

final class PhorgeVerifyEmailUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'email-verify';

  public function getLogTypeName() {
    return pht('Email: Verify Address');
  }

}
