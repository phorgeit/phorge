<?php

final class PhorgeAddEmailUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'email-add';

  public function getLogTypeName() {
    return pht('Email: Add Address');
  }

}
