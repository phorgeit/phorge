<?php

final class PhorgeConfigManagementListWorkflow
  extends PhorgeConfigManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('list')
      ->setExamples('**list**')
      ->setSynopsis(pht('List all configuration keys.'));
  }

  public function execute(PhutilArgumentParser $args) {
    $options = PhorgeApplicationConfigOptions::loadAllOptions();
    ksort($options);

    $console = PhutilConsole::getConsole();
    foreach ($options as $option) {
      $console->writeOut($option->getKey()."\n");
    }

    return 0;
  }

}
