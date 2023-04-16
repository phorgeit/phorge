<?php

abstract class PhorgeTokenDAO extends PhorgeLiskDAO {

  public function getApplicationName() {
    return 'token';
  }

}
