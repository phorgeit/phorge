<?php

final class PhorgeProjectDescriptionField
  extends PhorgeProjectStandardCustomField {

  public function createFields($object) {
    return PhorgeStandardCustomField::buildStandardFields(
      $this,
      array(
        'description' => array(
          'name'        => pht('Description'),
          'type'        => 'remarkup',
          'description' => pht('Short project description.'),
          'fulltext'    => PhorgeSearchDocumentFieldType::FIELD_BODY,
        ),
      ),
      $internal = true);
  }

}
