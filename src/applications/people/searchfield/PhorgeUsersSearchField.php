<?php

final class PhorgeUsersSearchField
  extends PhorgeSearchTokenizerField {

  protected function getDefaultValue() {
    return array();
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    return $this->getUsersFromRequest($request, $key);
  }

  protected function newDatasource() {
    return new PhorgePeopleUserFunctionDatasource();
  }

  protected function newConduitParameterType() {
    return new ConduitUserListParameterType();
  }

}
