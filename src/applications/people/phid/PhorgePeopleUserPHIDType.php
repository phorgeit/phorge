<?php

final class PhorgePeopleUserPHIDType extends PhorgePHIDType {

  const TYPECONST = 'USER';

  public function getTypeName() {
    return pht('User');
  }

  public function getTypeIcon() {
    return 'fa-user bluegrey';
  }

  public function newObject() {
    return new PhorgeUser();
  }

  public function getPHIDTypeApplicationClass() {
    return 'PhorgePeopleApplication';
  }

  protected function buildQueryForObjects(
    PhorgeObjectQuery $query,
    array $phids) {

    return id(new PhorgePeopleQuery())
      ->withPHIDs($phids)
      ->needProfile(true)
      ->needProfileImage(true)
      ->needAvailability(true);
  }

  public function loadHandles(
    PhorgeHandleQuery $query,
    array $handles,
    array $objects) {

    foreach ($handles as $phid => $handle) {
      $user = $objects[$phid];
      $realname = $user->getRealName();
      $username = $user->getUsername();

      $handle
        ->setName($username)
        ->setURI('/p/'.$username.'/')
        ->setFullName($user->getFullName())
        ->setImageURI($user->getProfileImageURI())
        ->setMailStampName('@'.$username);

      if ($user->getIsMailingList()) {
        $handle->setIcon('fa-envelope-o');
        $handle->setSubtitle(pht('Mailing List'));
      } else {
        $profile = $user->getUserProfile();
        $icon_key = $profile->getIcon();
        $icon_icon = PhorgePeopleIconSet::getIconIcon($icon_key);
        $subtitle = $profile->getDisplayTitle();

        $handle
          ->setIcon($icon_icon)
          ->setSubtitle($subtitle)
          ->setTokenIcon('fa-user');
      }

      $availability = null;
      if ($user->getIsDisabled()) {
        $availability = PhorgeObjectHandle::AVAILABILITY_DISABLED;
      } else if (!$user->isResponsive()) {
        $availability = PhorgeObjectHandle::AVAILABILITY_NOEMAIL;
      } else {
        $until = $user->getAwayUntil();
        if ($until) {
          $away = PhorgeCalendarEventInvitee::AVAILABILITY_AWAY;
          if ($user->getDisplayAvailability() == $away) {
            $availability = PhorgeObjectHandle::AVAILABILITY_NONE;
          } else {
            $availability = PhorgeObjectHandle::AVAILABILITY_PARTIAL;
          }
        }
      }

      if ($availability) {
        $handle->setAvailability($availability);
      }
    }
  }

  public function canLoadNamedObject($name) {
    return preg_match('/^@.+/', $name);
  }

  public function loadNamedObjects(
    PhorgeObjectQuery $query,
    array $names) {

    $id_map = array();
    foreach ($names as $name) {
      $id = substr($name, 1);
      $id = phutil_utf8_strtolower($id);
      $id_map[$id][] = $name;
    }

    $objects = id(new PhorgePeopleQuery())
      ->setViewer($query->getViewer())
      ->withUsernames(array_keys($id_map))
      ->execute();

    $results = array();
    foreach ($objects as $id => $object) {
      $user_key = $object->getUsername();
      $user_key = phutil_utf8_strtolower($user_key);
      foreach (idx($id_map, $user_key, array()) as $name) {
        $results[$name] = $object;
      }
    }

    return $results;
  }

}
