<?php

final class PhabricatorAphlictManagementForegroundWorkflow
  extends PhabricatorAphlictManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('foreground')
      ->setSynopsis(
        pht(
          'Start the notifications server in the foreground.'))
      ->setArguments($this->getLaunchArguments());
  }

  public function execute(PhutilArgumentParser $args) {
    $this->parseLaunchArguments($args);

    $this->willLaunch();
    return $this->launch();
  }

}
