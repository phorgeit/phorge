<?php

abstract class HarbormasterWorker extends PhorgeWorker {

  public function getViewer() {
    return PhorgeUser::getOmnipotentUser();
  }

}
