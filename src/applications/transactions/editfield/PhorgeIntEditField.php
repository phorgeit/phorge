<?php

final class PhorgeIntEditField
  extends PhorgeEditField {

  protected function newControl() {
    return new AphrontFormTextControl();
  }

  protected function newHTTPParameterType() {
    return new AphrontIntHTTPParameterType();
  }

  protected function newConduitParameterType() {
    return new ConduitIntParameterType();
  }

}
