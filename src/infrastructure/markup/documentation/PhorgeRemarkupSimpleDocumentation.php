<?php

final class PhorgeRemarkupSimpleDocumentation
  extends PhorgeRemarkupDocumentation {

  private $title;
  private $content;
  private $examples = array();

  public function setTitle($title) {
    $this->title = $title;
    return $this;
  }

  public function getTitle() {
    return $this->title;
  }

  public function setContent($content) {
    $this->content = $content;
    return $this;
  }

  public function addExample($example) {
    $this->examples[] = $example;
    return $this;
  }

  public function getExamples() {
    return $this->examples;
  }

  public function getContent() {
    return "= {$this->title}\n{$this->content}";
  }

}
