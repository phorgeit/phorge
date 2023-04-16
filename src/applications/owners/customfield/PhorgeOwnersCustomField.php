<?php

abstract class PhorgeOwnersCustomField
  extends PhorgeCustomField {

  public function newStorageObject() {
    return new PhorgeOwnersCustomFieldStorage();
  }

  protected function newStringIndexStorage() {
    return new PhorgeOwnersCustomFieldStringIndex();
  }

  protected function newNumericIndexStorage() {
    return new PhorgeOwnersCustomFieldNumericIndex();
  }

}
