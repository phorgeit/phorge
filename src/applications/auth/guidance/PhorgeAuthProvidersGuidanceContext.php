<?php

final class PhorgeAuthProvidersGuidanceContext
  extends PhorgeGuidanceContext {

  private $canManage = false;

  public function setCanManage($can_manage) {
    $this->canManage = $can_manage;
    return $this;
  }

  public function getCanManage() {
    return $this->canManage;
  }

}
