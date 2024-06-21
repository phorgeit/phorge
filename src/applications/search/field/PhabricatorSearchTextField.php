<?php

final class PhabricatorSearchTextField
  extends PhabricatorSearchField {

  protected function getDefaultValue() {
    return '';
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    return $request->getStr($key);
  }

  protected function validateControlValue($value) {
    if (!is_array($value)) {
      return;
    }
    $this->addError(
      pht('Invalid'),
      pht('Text value for "%s" can not be parsed.', $this->getLabel()));
  }

  protected function newControl() {
    return new AphrontFormTextControl();
  }

  protected function newConduitParameterType() {
    return new ConduitStringParameterType();
  }

}
