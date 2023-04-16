<?php

final class PhorgeUserPHIDResolver
  extends PhorgePHIDResolver {

  protected function getResolutionMap(array $names) {
    // Pick up the normalization and case rules from the PHID type query.

    foreach ($names as $key => $name) {
      $names[$key] = '@'.$name;
    }

    $query = id(new PhorgeObjectQuery())
      ->setViewer($this->getViewer());

    $users = id(new PhorgePeopleUserPHIDType())
      ->loadNamedObjects($query, $names);

    $results = array();
    foreach ($users as $at_username => $user) {
      $results[substr($at_username, 1)] = $user->getPHID();
    }

    return $results;
  }

}
