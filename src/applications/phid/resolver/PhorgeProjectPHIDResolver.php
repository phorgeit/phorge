<?php

final class PhorgeProjectPHIDResolver
  extends PhorgePHIDResolver {

  protected function getResolutionMap(array $names) {
    // This is a little awkward but we want to pick up the normalization
    // rules from the PHIDType. This flow could perhaps be made cleaner.

    foreach ($names as $key => $name) {
      $names[$key] = '#'.$name;
    }

    $query = id(new PhorgeObjectQuery())
      ->setViewer($this->getViewer());

    $projects = id(new PhorgeProjectProjectPHIDType())
      ->loadNamedObjects($query, $names);

    $results = array();
    foreach ($projects as $hashtag => $project) {
      $results[substr($hashtag, 1)] = $project->getPHID();
    }

    return $results;
  }

}
