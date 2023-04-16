<?php

abstract class PhorgeProjectCustomField
  extends PhorgeCustomField {

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
