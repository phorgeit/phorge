<?php

final class PhorgeEvent extends PhutilEvent {

  private $user;
  private $aphrontRequest;
  private $conduitRequest;

  public function setUser(PhorgeUser $user) {
    $this->user = $user;
    return $this;
  }

  public function getUser() {
    return $this->user;
  }

  public function setAphrontRequest(AphrontRequest $aphront_request) {
    $this->aphrontRequest = $aphront_request;
    return $this;
  }

  public function getAphrontRequest() {
    return $this->aphrontRequest;
  }

  public function setConduitRequest(ConduitAPIRequest $conduit_request) {
    $this->conduitRequest = $conduit_request;
    return $this;
  }

  public function getConduitRequest() {
    return $this->conduitRequest;
  }

}
