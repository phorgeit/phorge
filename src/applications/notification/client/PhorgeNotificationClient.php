<?php

final class PhorgeNotificationClient extends Phobject {

  public static function tryAnyConnection() {
    $servers = PhorgeNotificationServerRef::getEnabledAdminServers();

    if (!$servers) {
      return;
    }

    foreach ($servers as $server) {
      $server->loadServerStatus();
      return;
    }

    return;
  }

  public static function tryToPostMessage(array $data) {
    $unique_id = Filesystem::readRandomCharacters(32);
    $data = $data + array(
      'uniqueID' => $unique_id,
    );

    $servers = PhorgeNotificationServerRef::getEnabledAdminServers();

    shuffle($servers);

    foreach ($servers as $server) {
      try {
        $server->postMessage($data);
        return;
      } catch (Exception $ex) {
        // Just ignore any issues here.
      }
    }
  }

  public static function isEnabled() {
    return (bool)PhorgeNotificationServerRef::getEnabledAdminServers();
  }

}
