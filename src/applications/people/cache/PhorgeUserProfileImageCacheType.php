<?php

final class PhorgeUserProfileImageCacheType
  extends PhorgeUserCacheType {

  const CACHETYPE = 'user.profile';

  const KEY_URI = 'user.profile.image.uri.v1';

  public function getAutoloadKeys() {
    return array(
      self::KEY_URI,
    );
  }

  public function canManageKey($key) {
    return ($key === self::KEY_URI);
  }

  public function getDefaultValue() {
    return PhorgeUser::getDefaultProfileImageURI();
  }

  public function newValueForUsers($key, array $users) {
    $viewer = $this->getViewer();

    $file_phids = array();
    $generate_users = array();
    foreach ($users as $user) {
      $user_phid = $user->getPHID();
      $custom_phid = $user->getProfileImagePHID();
      $default_phid = $user->getDefaultProfileImagePHID();
      $version = $user->getDefaultProfileImageVersion();

      if ($custom_phid) {
        $file_phids[$user_phid] = $custom_phid;
        continue;
      }
      if ($default_phid) {
        if ($version == PhorgeFilesComposeAvatarBuiltinFile::VERSION) {
          $file_phids[$user_phid] = $default_phid;
          continue;
        }
      }
      $generate_users[] = $user;
    }

    $generator = new PhorgeFilesComposeAvatarBuiltinFile();
    foreach ($generate_users as $user) {
      $file = $generator->updateUser($user);
      $file_phids[$user->getPHID()] = $file->getPHID();
    }

    if ($file_phids) {
      $files = id(new PhorgeFileQuery())
        ->setViewer($viewer)
        ->withPHIDs($file_phids)
        ->execute();
      $files = mpull($files, null, 'getPHID');
    } else {
      $files = array();
    }

    $results = array();
    foreach ($users as $user) {
      $image_phid = $user->getProfileImagePHID();
      $default_phid = $user->getDefaultProfileImagePHID();
      if (isset($files[$image_phid])) {
        $image_uri = $files[$image_phid]->getBestURI();
      } else if (isset($files[$default_phid])) {
        $image_uri = $files[$default_phid]->getBestURI();
      } else {
        $image_uri = PhorgeUser::getDefaultProfileImageURI();
      }

      $user_phid = $user->getPHID();
      $version = $this->getCacheVersion($user);
      $results[$user_phid] = "{$version},{$image_uri}";
    }

    return $results;
  }

  public function getValueFromStorage($value) {
    $parts = explode(',', $value, 2);
    return end($parts);
  }

  public function shouldValidateRawCacheData() {
    return true;
  }

  public function isRawCacheDataValid(PhorgeUser $user, $key, $data) {
    $parts = explode(',', $data, 2);
    $version = reset($parts);
    return ($version === $this->getCacheVersion($user));
  }

  private function getCacheVersion(PhorgeUser $user) {
    $parts = array(
      PhorgeEnv::getCDNURI('/'),
      PhorgeEnv::getEnvConfig('cluster.instance'),
      $user->getProfileImagePHID(),
    );
    $parts = serialize($parts);
    return PhorgeHash::digestForIndex($parts);
  }

}
