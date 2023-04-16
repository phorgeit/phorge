<?php

abstract class PhorgeConfigEntryDAO extends PhorgeLiskDAO {

  public function getApplicationName() {
    return 'config';
  }

}
