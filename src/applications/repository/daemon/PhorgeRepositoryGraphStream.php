<?php

abstract class PhorgeRepositoryGraphStream extends Phobject {

  abstract public function getParents($commit);
  abstract public function getCommitDate($commit);

}
