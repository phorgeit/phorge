<?php

final class PhorgeUserConfiguredCustomField
  extends PhorgeUserCustomField
  implements PhorgeStandardCustomFieldInterface {

  public function getStandardCustomFieldNamespace() {
    return 'user';
  }

  public function createFields($object) {
    return PhorgeStandardCustomField::buildStandardFields(
      $this,
      PhorgeEnv::getEnvConfig('user.custom-field-definitions'));
  }

  public function newStorageObject() {
    return new PhorgeUserConfiguredCustomFieldStorage();
  }

  protected function newStringIndexStorage() {
    return new PhorgeUserCustomFieldStringIndex();
  }

  protected function newNumericIndexStorage() {
    return new PhorgeUserCustomFieldNumericIndex();
  }

}
