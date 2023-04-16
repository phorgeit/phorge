<?php

final class PhorgeSearchTextField
  extends PhorgeSearchField {

  protected function getDefaultValue() {
    return '';
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    return $request->getStr($key);
  }

  protected function newControl() {
    return new AphrontFormTextControl();
  }

  protected function newConduitParameterType() {
    return new ConduitStringParameterType();
  }

}
