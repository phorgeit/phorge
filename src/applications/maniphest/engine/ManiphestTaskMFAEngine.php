<?php

final class ManiphestTaskMFAEngine
  extends PhorgeEditEngineMFAEngine {

  public function shouldRequireMFA() {
    $status = $this->getObject()->getStatus();
    return ManiphestTaskStatus::isMFAStatus($status);
  }

}
