<?php

final class PhorgePrimaryEmailUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'email-primary';

  public function getLogTypeName() {
    return pht('Email: Change Primary');
  }

}
