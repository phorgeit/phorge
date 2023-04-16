<?php

final class PhorgeSubmitEditField
  extends PhorgeEditField {

  protected function renderControl() {
    return id(new AphrontFormSubmitControl())
      ->setValue($this->getValue());
  }

  protected function newHTTPParameterType() {
    return null;
  }

  protected function newConduitParameterType() {
    return null;
  }

}
