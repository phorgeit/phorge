<?php

abstract class NuanceQuery extends PhorgeCursorPagedPolicyAwareQuery {

  public function getQueryApplicationClass() {
    return 'PhorgeNuanceApplication';
  }

}
