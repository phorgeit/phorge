<?php

abstract class PhorgeOAuthServerDAO extends PhorgeLiskDAO {

  public function getApplicationName() {
    return 'oauth_server';
  }

}
