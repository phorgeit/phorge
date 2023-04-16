<?php

final class PhorgeEditEnginePointsCommentAction
  extends PhorgeEditEngineCommentAction {

  public function getPHUIXControlType() {
    return 'points';
  }

  public function getPHUIXControlSpecification() {
    return array(
      'value' => $this->getValue(),
    );
  }

}
