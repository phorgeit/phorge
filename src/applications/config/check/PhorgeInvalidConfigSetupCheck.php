<?php

final class PhorgeInvalidConfigSetupCheck extends PhorgeSetupCheck {

  public function getDefaultGroup() {
    return self::GROUP_OTHER;
  }

  protected function executeChecks() {
    $groups = PhorgeApplicationConfigOptions::loadAll();
    foreach ($groups as $group) {
      $options = $group->getOptions();
      foreach ($options as $option) {
        try {
          $group->validateOption(
            $option,
            PhorgeEnv::getUnrepairedEnvConfig($option->getKey()));
        } catch (PhorgeConfigValidationException $ex) {
          $this
            ->newIssue('config.invalid.'.$option->getKey())
            ->setName(pht("Config '%s' Invalid", $option->getKey()))
            ->setMessage(
              pht(
                "Configuration option '%s' has invalid value and ".
                "was restored to the default: %s",
                $option->getKey(),
                $ex->getMessage()))
            ->addPhorgeConfig($option->getKey());
        }
      }
    }
  }

}
