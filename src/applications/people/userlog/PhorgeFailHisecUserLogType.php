<?php

final class PhorgeFailHisecUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'hisec-fail';

  public function getLogTypeName() {
    return pht('Hisec: Failed Attempt');
  }

}
