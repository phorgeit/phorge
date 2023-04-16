<?php

final class PhorgeOwnerPathQuery extends Phobject {

  public static function loadAffectedPaths(
    PhorgeRepository $repository,
    PhorgeRepositoryCommit $commit,
    PhorgeUser $user) {

    $drequest = DiffusionRequest::newFromDictionary(
      array(
        'user'        => $user,
        'repository'  => $repository,
        'commit'      => $commit->getCommitIdentifier(),
      ));

    $path_query = DiffusionPathChangeQuery::newFromDiffusionRequest(
      $drequest);
    $paths = $path_query->loadChanges();

    $result = array();
    foreach ($paths as $path) {
      $basic_path = '/'.$path->getPath();
      if ($path->getFileType() == DifferentialChangeType::FILE_DIRECTORY) {
        $basic_path = rtrim($basic_path, '/').'/';
      }
      $result[] = $basic_path;
    }
    return $result;
  }

}
