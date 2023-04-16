<?php

final class PhorgeCacheSetupCheck extends PhorgeSetupCheck {

  public function getDefaultGroup() {
    return self::GROUP_PHP;
  }

  protected function executeChecks() {
    $code_cache = PhorgeOpcodeCacheSpec::getActiveCacheSpec();
    $data_cache = PhorgeDataCacheSpec::getActiveCacheSpec();

    $issues = $code_cache->getIssues() + $data_cache->getIssues();

    foreach ($issues as $issue) {
      $this->addIssue($issue);
    }
  }

}
