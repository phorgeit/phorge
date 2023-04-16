<?php

final class DiffusionGitLFSTemporaryTokenType
  extends PhorgeAuthTemporaryTokenType {

  const TOKENTYPE = 'diffusion.git.lfs';
  const HTTP_USERNAME = '@git-lfs';

  public function getTokenTypeDisplayName() {
    return pht('Git Large File Storage');
  }

  public function getTokenReadableTypeName(
    PhorgeAuthTemporaryToken $token) {
    return pht('Git LFS Token');
  }

  public static function newHTTPAuthorization(
    PhorgeRepository $repository,
    PhorgeUser $viewer,
    $operation) {

    $lfs_user = self::HTTP_USERNAME;
    $lfs_pass = Filesystem::readRandomCharacters(32);
    $lfs_hash = PhorgeHash::weakDigest($lfs_pass);

    $ttl = PhorgeTime::getNow() + phutil_units('1 day in seconds');

    $token = id(new PhorgeAuthTemporaryToken())
      ->setTokenResource($repository->getPHID())
      ->setTokenType(self::TOKENTYPE)
      ->setTokenCode($lfs_hash)
      ->setUserPHID($viewer->getPHID())
      ->setTemporaryTokenProperty('lfs.operation', $operation)
      ->setTokenExpires($ttl)
      ->save();

    $authorization_header = base64_encode($lfs_user.':'.$lfs_pass);
    return 'Basic '.$authorization_header;
  }

}
