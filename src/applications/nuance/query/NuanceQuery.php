<?php

/**
 * @template R of PhabricatorPolicyInterface
 * @extends PhabricatorCursorPagedPolicyAwareQuery<R>
 */
abstract class NuanceQuery extends PhabricatorCursorPagedPolicyAwareQuery {

  public function getQueryApplicationClass() {
    return PhabricatorNuanceApplication::class;
  }

}
