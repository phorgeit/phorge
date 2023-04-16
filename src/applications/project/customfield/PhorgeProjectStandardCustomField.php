<?php

abstract class PhorgeProjectStandardCustomField
  extends PhorgeProjectCustomField
  implements PhorgeStandardCustomFieldInterface {

  public function getStandardCustomFieldNamespace() {
    return 'project:internal';
  }

  public function newStorageObject() {
    return new PhorgeProjectCustomFieldStorage();
  }

  protected function newStringIndexStorage() {
    return new PhorgeProjectCustomFieldStringIndex();
  }

  protected function newNumericIndexStorage() {
    return new PhorgeProjectCustomFieldNumericIndex();
  }

}
