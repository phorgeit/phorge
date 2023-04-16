<?php

abstract class BulkParameterType extends Phobject {

  private $viewer;
  private $field;

  final public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  final public function getViewer() {
    return $this->viewer;
  }

  final public function setField(PhorgeEditField $field) {
    $this->field = $field;
    return $this;
  }

  final public function getField() {
    return $this->field;
  }

  abstract public function getPHUIXControlType();

  public function getPHUIXControlSpecification() {
    return array(
      'value' => null,
    );
  }

}
