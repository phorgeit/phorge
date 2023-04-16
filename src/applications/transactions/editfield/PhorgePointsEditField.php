<?php

final class PhorgePointsEditField
  extends PhorgeEditField {

  protected function newControl() {
    return new AphrontFormTextControl();
  }

  protected function newConduitParameterType() {
    return new ConduitPointsParameterType();
  }

  protected function newCommentAction() {
    return id(new PhorgeEditEnginePointsCommentAction());
  }

  protected function newBulkParameterType() {
    return new BulkPointsParameterType();
  }

}
