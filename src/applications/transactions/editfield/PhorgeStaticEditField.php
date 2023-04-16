<?php

final class PhorgeStaticEditField
  extends PhorgeEditField {

  protected function newControl() {
    return new AphrontFormMarkupControl();
  }

  protected function newHTTPParameterType() {
    return null;
  }

  protected function newConduitParameterType() {
    return null;
  }

}
