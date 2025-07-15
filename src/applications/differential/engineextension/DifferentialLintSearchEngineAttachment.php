<?php

// TM CHANGES BEGIN: Implementation of "lint" attachment

final class DifferentialLintSearchEngineAttachment
  extends PhabricatorSearchEngineAttachment {

  public function getAttachmentName() {
    return pht('Lint');
  }

  public function getAttachmentDescription() {
    return pht('Get the lint status of each diff.');
  }

  public function getAttachmentForObject($object, $data, $spec) {
    $status_value = $object->getLintStatus();
    $status = DifferentialLintStatus::newStatusFromValue($status_value);

    return array(
      'status' => array(
        'value' => $status->getValue(),
        'name' => $status->getName(),
      ),
    );
  }

// TM CHANGES END

}
