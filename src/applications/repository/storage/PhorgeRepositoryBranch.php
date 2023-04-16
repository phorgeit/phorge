<?php

final class PhorgeRepositoryBranch extends PhorgeRepositoryDAO {

  protected $repositoryID;
  protected $name;
  protected $lintCommit;

  protected function getConfiguration() {
    return array(
      self::CONFIG_COLUMN_SCHEMA => array(
        'name' => 'text128',
        'lintCommit' => 'text40?',
      ),
      self::CONFIG_KEY_SCHEMA => array(
        'repositoryID' => array(
          'columns' => array('repositoryID', 'name'),
          'unique' => true,
        ),
      ),
    ) + parent::getConfiguration();
  }

  public static function loadBranch($repository_id, $branch_name) {
    return id(new PhorgeRepositoryBranch())->loadOneWhere(
      'repositoryID = %d AND name = %s',
      $repository_id,
      $branch_name);
  }

  public static function loadOrCreateBranch($repository_id, $branch_name) {
    $branch = self::loadBranch($repository_id, $branch_name);
    if ($branch) {
      return $branch;
    }

    return id(new PhorgeRepositoryBranch())
      ->setRepositoryID($repository_id)
      ->setName($branch_name)
      ->save();
  }

}
