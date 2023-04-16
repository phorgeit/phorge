<?php

final class PhorgeSearchSubscribersField
  extends PhorgeSearchTokenizerField {

  protected function getDefaultValue() {
    return array();
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    $allow_types = array(
      PhorgeProjectProjectPHIDType::TYPECONST,
      PhorgeOwnersPackagePHIDType::TYPECONST,
    );
    return $this->getUsersFromRequest($request, $key, $allow_types);
  }

  protected function newDatasource() {
    return new PhorgeMetaMTAMailableFunctionDatasource();
  }

  protected function newConduitParameterType() {
    // TODO: Ideally, this should eventually be a "Subscribers" type which
    // accepts projects as well.
    return new ConduitUserListParameterType();
  }

}
