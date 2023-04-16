<?php

final class PhorgeUserConfigOptions
  extends PhorgeApplicationConfigOptions {

  public function getName() {
    return pht('User Profiles');
  }

  public function getDescription() {
    return pht('User profiles configuration.');
  }

  public function getIcon() {
    return 'fa-users';
  }

  public function getGroup() {
    return 'apps';
  }

  public function getOptions() {

    $default = array(
      id(new PhorgeUserRealNameField())->getFieldKey() => true,
      id(new PhorgeUserTitleField())->getFieldKey() => true,
      id(new PhorgeUserIconField())->getFieldKey() => true,
      id(new PhorgeUserSinceField())->getFieldKey() => true,
      id(new PhorgeUserRolesField())->getFieldKey() => true,
      id(new PhorgeUserStatusField())->getFieldKey() => true,
      id(new PhorgeUserBlurbField())->getFieldKey() => true,
    );

    foreach ($default as $key => $enabled) {
      $default[$key] = array(
        'disabled' => !$enabled,
      );
    }

    $custom_field_type = 'custom:PhorgeCustomFieldConfigOptionType';

    return array(
      $this->newOption('user.fields', $custom_field_type, $default)
        ->setCustomData(id(new PhorgeUser())->getCustomFieldBaseClass())
        ->setDescription(pht('Select and reorder user profile fields.')),
      $this->newOption('user.custom-field-definitions', 'wild', array())
        ->setDescription(pht('Add new simple fields to user profiles.')),
      $this->newOption('user.require-real-name', 'bool', true)
        ->setDescription(pht('Always require real name for user profiles.'))
        ->setBoolOptions(
          array(
            pht('Make real names required'),
            pht('Make real names optional'),
          )),
    );
  }

}
