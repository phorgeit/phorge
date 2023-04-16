<?php

final class ManiphestConfiguredCustomField
  extends ManiphestCustomField
  implements PhorgeStandardCustomFieldInterface {

  public function getStandardCustomFieldNamespace() {
    return 'maniphest';
  }

  public function createFields($object) {
    $config = PhorgeEnv::getEnvConfig(
      'maniphest.custom-field-definitions');
    $fields = PhorgeStandardCustomField::buildStandardFields(
      $this,
      $config);

    return $fields;
  }

}
