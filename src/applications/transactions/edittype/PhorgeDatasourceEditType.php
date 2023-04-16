<?php

final class PhorgeDatasourceEditType
  extends PhorgePHIDListEditType {

  public function generateTransactions(
    PhorgeApplicationTransaction $template,
    array $spec) {

    $value = idx($spec, 'value');

    $xaction = $this->newTransaction($template)
      ->setNewValue($value);

    return array($xaction);
  }

  public function getValueDescription() {
    return '?';
  }

}
