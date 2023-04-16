<?php

final class PhorgeCommentEditField
  extends PhorgeEditField {

  private $isWebOnly;

  public function setIsWebOnly($is_web_only) {
    $this->isWebOnly = $is_web_only;
    return $this;
  }

  public function getIsWebOnly() {
    return $this->isWebOnly;
  }

  protected function newControl() {
    return new PhorgeRemarkupControl();
  }

  protected function newEditType() {
    return new PhorgeCommentEditType();
  }

  protected function newConduitParameterType() {
    if ($this->getIsWebOnly()) {
      return null;
    } else {
      return new ConduitStringParameterType();
    }
  }

  public function shouldGenerateTransactionsFromSubmit() {
    return !$this->isPrimaryCommentField();
  }

  public function shouldGenerateTransactionsFromComment() {
    return $this->isPrimaryCommentField();
  }

  private function isPrimaryCommentField() {
    return ($this->getKey() === 'comment');
  }

}
