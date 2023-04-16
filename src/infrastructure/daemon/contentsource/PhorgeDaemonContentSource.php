<?php

final class PhorgeDaemonContentSource
  extends PhorgeContentSource {

  const SOURCECONST = 'daemon';

  public function getSourceName() {
    return pht('Daemon');
  }

  public function getSourceDescription() {
    return pht('Updates from background processing in daemons.');
  }

}
