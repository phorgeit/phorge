<?php

final class PhorgeEnterHisecUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'hisec-enter';

  public function getLogTypeName() {
    return pht('Hisec: Enter');
  }

}
