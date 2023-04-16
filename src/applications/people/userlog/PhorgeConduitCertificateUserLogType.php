<?php

final class PhorgeConduitCertificateUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'conduit-cert';

  public function getLogTypeName() {
    return pht('Conduit: Read Certificate');
  }

}
