<?php

final class PhorgeRepositoryCommitPHIDType extends PhorgePHIDType {

  const TYPECONST = 'CMIT';

  public function getTypeName() {
    return pht('Diffusion Commit');
  }

  public function newObject() {
    return new PhorgeRepositoryCommit();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new DiffusionCommitQuery())
      ->withPHIDs($phids);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    $unreachable = array();
    foreach ($handles as $phid => $handle) {
      $commit = $objects[$phid];
      if ($commit->isUnreachable()) {
        $unreachable[$phid] = $commit;
      }
    }

    if ($unreachable) {
      $query = id(new DiffusionCommitHintQuery())
        ->setViewer($query->getViewer())
        ->withCommits($unreachable);

      $query->execute();

      $hints = $query->getCommitMap();
    } else {
      $hints = array();
    }

    foreach ($handles as $phid => $handle) {
      $commit = $objects[$phid];
      $repository = $commit->getRepository();
      $commit_identifier = $commit->getCommitIdentifier();

      $name = $repository->formatCommitName($commit_identifier);

      if ($commit->isUnreachable()) {
        $handle->setStatus(PhorgeObjectHandle::STATUS_CLOSED);

        // If we have a hint about this commit being rewritten, add the
        // rewrite target to the handle name. This reduces the chance users
        // will be caught offguard by the rewrite.
        $hint = idx($hints, $phid);
        if ($hint && $hint->isRewritten()) {
          $new_name = $hint->getNewCommitIdentifier();
          $new_name = $repository->formatCommitName($new_name);
          $name = pht("%s \xE2\x99\xBB %s",  $name, $new_name);
        }
      }

      $summary = $commit->getSummary();
      if (strlen($summary)) {
        $full_name = $name.': '.$summary;
      } else {
        $full_name = $name;
      }

      $handle->setName($name);
      $handle->setFullName($full_name);
      $handle->setURI($commit->getURI());
      $handle->setTimestamp($commit->getEpoch());
    }
  }

  public static function getCommitObjectNamePattern() {
    $min_unqualified = PhorgeRepository::MINIMUM_UNQUALIFIED_HASH;
    $min_qualified   = PhorgeRepository::MINIMUM_QUALIFIED_HASH;

    return
      '(?:r[A-Z]+:?|R[0-9]+:)[1-9]\d*'.
      '|'.
      '(?:r[A-Z]+:?|R[0-9]+:)[a-f0-9]{'.$min_qualified.',40}'.
      '|'.
      '[a-f0-9]{'.$min_unqualified.',40}';
  }

  public function canLoadNamedObject($name) {
    $pattern = self::getCommitObjectNamePattern();
    return preg_match('(^'.$pattern.'$)', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $query = id(new DiffusionCommitQuery())
      ->setViewer($query->getViewer())
      ->withIdentifiers($names);

    $query->execute();

    return $query->getIdentifierMap();
  }

}
