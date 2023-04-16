<?php

abstract class DiffusionCommitHeraldField extends HeraldField {

  public function supportsObject($object) {
    return ($object instanceof PhorgeRepositoryCommit);
  }

  public function getFieldGroupKey() {
    return DiffusionCommitHeraldFieldGroup::FIELDGROUPKEY;
  }

}
