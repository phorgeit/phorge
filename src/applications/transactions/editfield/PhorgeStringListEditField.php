<?php

final class PhorgeStringListEditField
  extends PhorgeEditField {

  protected function newControl() {
    return new AphrontFormTextControl();
  }

  protected function getValueForControl() {
    $value = $this->getValue();
    return implode(', ', $value);
  }

  protected function newConduitParameterType() {
    return new ConduitStringListParameterType();
  }

  protected function newHTTPParameterType() {
    return new AphrontStringListHTTPParameterType();
  }

}
