<?php

final class HarbormasterPrototypeBuildStepGroup
  extends HarbormasterBuildStepGroup {

  const GROUPKEY = 'harbormaster.prototype';

  public function getGroupName() {
    return pht('Prototypes');
  }

  public function getGroupOrder() {
    return 8000;
  }

  public function isEnabled() {
    return PhorgeEnv::getEnvConfig('phorge.show-prototypes');
  }

  public function shouldShowIfEmpty() {
    return false;
  }

}
