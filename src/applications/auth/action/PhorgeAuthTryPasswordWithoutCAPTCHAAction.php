<?php

final class PhorgeAuthTryPasswordWithoutCAPTCHAAction
  extends PhorgeSystemAction {

  const TYPECONST = 'auth.password-without-captcha';

  public function getScoreThreshold() {
    return 10 / phutil_units('1 hour in seconds');
  }

}
