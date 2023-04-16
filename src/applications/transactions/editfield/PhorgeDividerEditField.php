<?php

final class PhorgeDividerEditField
  extends PhorgeEditField {

  protected function renderControl() {
    return new AphrontFormDividerControl();
  }

  protected function newHTTPParameterType() {
    return null;
  }

  protected function newConduitParameterType() {
    return null;
  }

}
