<?php

final class PhorgeApplicationTransactionTemplatedCommentQuery
  extends PhorgeApplicationTransactionCommentQuery {

  private $template;

  public function setTemplate(
    PhorgeApplicationTransactionComment $template) {
    $this->template = $template;
    return $this;
  }

  protected function newApplicationTransactionCommentTemplate() {
    return id(clone $this->template);
  }

}
