<?php

final class PhorgePasteTransactionComment
  extends PhorgeApplicationTransactionComment {

  protected $lineNumber;
  protected $lineLength;

  public function getApplicationTransactionObject() {
    return new PhorgePasteTransaction();
  }

  public function shouldUseMarkupCache($field) {
    // Only cache submitted comments.
    return ($this->getTransactionPHID() != null);
  }

  protected function getConfiguration() {
    $config = parent::getConfiguration();
    $config[self::CONFIG_COLUMN_SCHEMA] = array(
      'lineNumber' => 'uint32?',
      'lineLength' => 'uint32?',
    ) + $config[self::CONFIG_COLUMN_SCHEMA];
    return $config;
  }

}
