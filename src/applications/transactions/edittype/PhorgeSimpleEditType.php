<?php

final class PhorgeSimpleEditType extends PhorgeEditType {

  public function generateTransactions(
    PhorgeApplicationTransaction $template,
    array $spec) {

    $edit = $this->newTransaction($template)
      ->setNewValue(idx($spec, 'value'));

    return array($edit);
  }

}
