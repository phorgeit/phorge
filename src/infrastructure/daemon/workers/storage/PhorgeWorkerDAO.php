<?php

abstract class PhorgeWorkerDAO extends PhorgeLiskDAO {

  public function getApplicationName() {
    return 'worker';
  }

}
