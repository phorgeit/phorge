<?php

final class PhorgeOwnersConfiguredCustomField
  extends PhorgeOwnersCustomField
  implements PhorgeStandardCustomFieldInterface {

  public function getStandardCustomFieldNamespace() {
    return 'owners';
  }

  public function createFields($object) {
    $config = PhorgeEnv::getEnvConfig('owners.custom-field-definitions');

    $fields = PhorgeStandardCustomField::buildStandardFields(
      $this,
      $config);

    return $fields;
  }

}
