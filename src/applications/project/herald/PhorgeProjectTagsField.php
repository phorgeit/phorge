<?php

abstract class PhorgeProjectTagsField
  extends HeraldField {

  public function getFieldGroupKey() {
    return HeraldSupportFieldGroup::FIELDGROUPKEY;
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeProjectInterface);
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return new PhorgeProjectDatasource();
  }

  final protected function getProjectTagsTransaction() {
    return $this->getAppliedEdgeTransactionOfType(
      PhorgeProjectObjectHasProjectEdgeType::EDGECONST);
  }

}
