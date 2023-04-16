<?php

final class PhorgeProjectConfiguredCustomField
  extends PhorgeProjectStandardCustomField
  implements PhorgeStandardCustomFieldInterface {

  public function getStandardCustomFieldNamespace() {
    return 'project';
  }

  public function createFields($object) {
    return PhorgeStandardCustomField::buildStandardFields(
      $this,
      PhorgeEnv::getEnvConfig('projects.custom-field-definitions'));
  }

}
