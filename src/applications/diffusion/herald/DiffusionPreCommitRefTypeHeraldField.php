<?php

final class DiffusionPreCommitRefTypeHeraldField
  extends DiffusionPreCommitRefHeraldField {

  const FIELDCONST = 'diffusion.pre.ref.type';

  public function getHeraldFieldName() {
    return pht('Ref type');
  }

  public function getHeraldFieldValue($object) {
    return $object->getRefType();
  }

  public function getHeraldFieldConditions() {
    return array(
      HeraldAdapter::CONDITION_IS,
      HeraldAdapter::CONDITION_IS_NOT,
    );
  }

  public function getHeraldFieldValueType($condition) {
    $types = array(
      PhorgeRepositoryPushLog::REFTYPE_BRANCH => pht('branch (git/hg)'),
      PhorgeRepositoryPushLog::REFTYPE_TAG => pht('tag (git)'),
      PhorgeRepositoryPushLog::REFTYPE_REF => pht('ref (git)'),
      PhorgeRepositoryPushLog::REFTYPE_BOOKMARK => pht('bookmark (hg)'),
    );

    return id(new HeraldSelectFieldValue())
      ->setKey(self::FIELDCONST)
      ->setOptions($types)
      ->setDefault(PhorgeRepositoryPushLog::REFTYPE_BRANCH);
  }

}
