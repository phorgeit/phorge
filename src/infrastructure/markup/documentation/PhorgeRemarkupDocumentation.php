<?php

abstract class PhorgeRemarkupDocumentation extends Phobject {

  private $rule;

  public function setRuleInstance($rule) {
    $this->rule = $rule;
    return $this;
  }

  final public function getKey() {
    return get_class($this->rule);
  }

  abstract public function getTitle();

  /**
   * For now, return a big string that will be processed by the remarkup engine.
   */
  abstract public function getContent();

  public function getExamples() {
    return array();
  }

  protected function getOrder() {
    return $this->rule->getPriority();
  }

  final public function getSortVector() {
    return id(new PhutilSortVector())
      ->addInt($this->getOrder())
      ->addString($this->getTitle())
      ->addString($this->getKey());
  }

}
