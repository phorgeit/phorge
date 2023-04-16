<?php

abstract class DrydockQuery extends PhorgeCursorPagedPolicyAwareQuery {

  public function getQueryApplicationClass() {
    return 'PhorgeDrydockApplication';
  }

}
