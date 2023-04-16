<?php

final class PhorgeOwnersSearchField
  extends PhorgeSearchTokenizerField {

  protected function getDefaultValue() {
    return array();
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    return $this->getUsersFromRequest($request, $key);
  }

  protected function newDatasource() {
    return new PhorgePeopleOwnerDatasource();
  }

  protected function newConduitParameterType() {
    return new ConduitUserListParameterType();
  }

}
