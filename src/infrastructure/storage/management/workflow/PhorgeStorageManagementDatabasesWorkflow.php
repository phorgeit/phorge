<?php

final class PhorgeStorageManagementDatabasesWorkflow
  extends PhorgeStorageManagementWorkflow {

  protected function didConstruct() {
    $this
      ->setName('databases')
      ->setExamples('**databases** [__options__]')
      ->setSynopsis(pht('List databases.'));
  }

  protected function isReadOnlyWorkflow() {
    return true;
  }

  public function didExecute(PhutilArgumentParser $args) {
    $api = $this->getAnyAPI();

    $patches = $this->getPatches();

    $databases = $api->getDatabaseList($patches, true);
    echo implode("\n", $databases)."\n";
    return 0;
  }

}
