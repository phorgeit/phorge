<?php

final class HeraldSpaceField extends HeraldField {

  const FIELDCONST = 'space';

  public function getHeraldFieldName() {
    return pht('Space');
  }

  public function getFieldGroupKey() {
    return HeraldSupportFieldGroup::FIELDGROUPKEY;
  }

  public function supportsObject($object) {
    return ($object instanceof PhorgeSpacesInterface);
  }

  public function getHeraldFieldValue($object) {
    return PhorgeSpacesNamespaceQuery::getObjectSpacePHID($object);
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_PHID;
  }

  protected function getDatasource() {
    return new PhorgeSpacesNamespaceDatasource();
  }

}
