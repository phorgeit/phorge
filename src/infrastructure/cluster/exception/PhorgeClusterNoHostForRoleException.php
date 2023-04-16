<?php

final class PhorgeClusterNoHostForRoleException
  extends Exception {

  public function __construct($role) {
    parent::__construct(pht('Search cluster has no hosts for role "%s".',
      $role));
  }
}
