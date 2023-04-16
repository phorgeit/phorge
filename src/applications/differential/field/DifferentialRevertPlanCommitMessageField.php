<?php

final class DifferentialRevertPlanCommitMessageField
  extends DifferentialCommitMessageCustomField {

  const FIELDKEY = 'revertPlan';

  public function getFieldName() {
    return pht('Revert Plan');
  }

  public function getCustomFieldKey() {
    return 'phorge:revert-plan';
  }

}
