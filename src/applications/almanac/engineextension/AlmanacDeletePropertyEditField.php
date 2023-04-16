<?php

final class AlmanacDeletePropertyEditField
  extends PhorgeEditField {

  protected function newControl() {
    return null;
  }

  protected function newHTTPParameterType() {
    return null;
  }

  protected function newConduitParameterType() {
    return new ConduitStringListParameterType();
  }

  protected function newEditType() {
    return new AlmanacDeletePropertyEditType();
  }

}
