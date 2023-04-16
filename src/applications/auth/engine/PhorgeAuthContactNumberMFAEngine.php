<?php

final class PhorgeAuthContactNumberMFAEngine
  extends PhorgeEditEngineMFAEngine {

  public function shouldTryMFA() {
    return true;
  }

}
