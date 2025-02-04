<?php

final class PhabricatorTokenSearchField
  extends PhabricatorSearchTokenizerField {

  protected function getDefaultValue() {
    return array();
  }

  protected function newDatasource() {
    return new PhabricatorTokenDatasource();
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    $list = $this->getListFromRequest($request, $key);

    $phids = array();
    $slugs = array();
    $token_type = PhabricatorTokenTokenPHIDType::TYPECONST;
    foreach ($list as $item) {
      $type = phid_get_type($item);
      if ($type == $token_type) {
        $phids[] = $item;
      }
    }

    return $phids;
  }

  protected function newConduitParameterType() {
    return new ConduitPHIDListParameterType();
  }

}
