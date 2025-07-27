<?php

/**
 * @template R of PhabricatorPolicyInterface
 * @extends PhabricatorCursorPagedPolicyAwareQuery<R>
 */
abstract class DrydockQuery extends PhabricatorCursorPagedPolicyAwareQuery {

  public function getQueryApplicationClass() {
    return PhabricatorDrydockApplication::class;
  }

}
