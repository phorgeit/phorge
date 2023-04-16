<?php

final class PhorgeSpacesSearchField
  extends PhorgeSearchTokenizerField {

  protected function getDefaultValue() {
    return array();
  }

  protected function newDatasource() {
    return new PhorgeSpacesNamespaceDatasource();
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    $viewer = $this->getViewer();
    $list = $this->getListFromRequest($request, $key);

    $type = new PhorgeSpacesNamespacePHIDType();
    $phids = array();
    $names = array();
    foreach ($list as $item) {
      if ($type->canLoadNamedObject($item)) {
        $names[] = $item;
      } else {
        $phids[] = $item;
      }
    }

    if ($names) {
      $spaces = id(new PhorgeObjectQuery())
        ->setViewer($viewer)
        ->withNames($names)
        ->execute();
      foreach (mpull($spaces, 'getPHID') as $phid) {
        $phids[] = $phid;
      }
      $phids = array_unique($phids);
    }

    return $phids;
  }

  protected function newConduitParameterType() {
    return new ConduitPHIDListParameterType();
  }

}
