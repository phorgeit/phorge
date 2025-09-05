<?php

/**
 * Implements not allowing to set a value for comparison in a boolean Herald
 * rule condition (e.g. for "Assignee | does not exist") or in a boolean Herald
 * rule action (e.g. "Do nothing" or "Require secure mail").
 */
final class HeraldEmptyFieldValue
  extends HeraldFieldValue {

  public function getFieldValueKey() {
    return 'none';
  }

  public function getControlType() {
    return self::CONTROL_NONE;
  }

  public function renderFieldValue($value) {
    return null;
  }

  public function renderEditorValue($value) {
    return null;
  }

}
