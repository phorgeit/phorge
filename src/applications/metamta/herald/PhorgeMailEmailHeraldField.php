<?php

abstract class PhorgeMailEmailHeraldField
  extends HeraldField {

  public function supportsObject($object) {
    return ($object instanceof PhorgeMetaMTAMail);
  }

  public function getFieldGroupKey() {
    return PhorgeMailEmailHeraldFieldGroup::FIELDGROUPKEY;
  }

}
