<?php

/**
 * This class should only be used for unit tests
 */
final class DifferentialTestCommitMessageField
  extends DifferentialCommitMessageField {
  public function getFieldName() { return 'Test'; }
  public function getFieldOrder() { return 1; }
}
