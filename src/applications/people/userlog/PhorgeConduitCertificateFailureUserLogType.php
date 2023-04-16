<?php

final class PhorgeConduitCertificateFailureUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'conduit-cert-fail';

  public function getLogTypeName() {
    return pht('Conduit: Read Certificate Failure');
  }

}
