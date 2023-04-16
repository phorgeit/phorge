<?php

final class PhorgeExitHisecUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'hisec-exit';

  public function getLogTypeName() {
    return pht('Hisec: Exit');
  }

}
