<?php

final class PhorgeSignDocumentsUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'login-legalpad';

  public function getLogTypeName() {
    return pht('Login: Signed Required Legalpad Documents');
  }

}
