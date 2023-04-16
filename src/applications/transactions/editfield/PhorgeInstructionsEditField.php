<?php

final class PhorgeInstructionsEditField
  extends PhorgeEditField {

  public function appendToForm(AphrontFormView $form) {
    return $form->appendRemarkupInstructions($this->getValue());
  }

  protected function newHTTPParameterType() {
    return null;
  }

  protected function newConduitParameterType() {
    return null;
  }

}
