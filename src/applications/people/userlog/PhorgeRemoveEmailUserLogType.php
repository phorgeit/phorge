<?php

final class PhorgeRemoveEmailUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'email-remove';

  public function getLogTypeName() {
    return pht('Email: Remove Address');
  }

}
