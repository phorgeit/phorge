<?php

final class PhorgeAuthFactorProviderMFAEngine
  extends PhorgeEditEngineMFAEngine {

  public function shouldTryMFA() {
    return true;
  }

}
