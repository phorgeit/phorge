<?php

final class PhorgeAphlictManagementStopWorkflow
  extends PhorgeAphlictManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('stop')
      ->setSynopsis(pht('Stop the notification server.'))
      ->setArguments($this->getLaunchArguments());
  }

  public function execute(PhutilArgumentParser $args) {
    $this->parseLaunchArguments($args);
    return $this->executeStopCommand();
  }

}
