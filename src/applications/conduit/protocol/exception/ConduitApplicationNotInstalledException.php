<?php

final class ConduitApplicationNotInstalledException
  extends ConduitMethodNotFoundException {

  public function __construct(ConduitAPIMethod $method, $application) {
    parent::__construct(
      pht(
        "Method '%s' belongs to application '%s', which is not enabled.",
        $method->getAPIMethodName(),
        $application));
  }

}
