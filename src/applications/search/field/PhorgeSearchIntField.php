<?php

final class PhorgeSearchIntField
  extends PhorgeSearchField {

  protected function getDefaultValue() {
    return null;
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    return $request->getInt($key);
  }

  protected function newControl() {
    return new AphrontFormTextControl();
  }

  protected function newConduitParameterType() {
    return new ConduitIntParameterType();
  }

}
