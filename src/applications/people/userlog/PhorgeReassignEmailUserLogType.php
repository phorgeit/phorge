<?php

final class PhorgeReassignEmailUserLogType
  extends PhorgeUserLogType {

  const LOGTYPE = 'email-reassign';

  public function getLogTypeName() {
    return pht('Email: Reassign');
  }

}
