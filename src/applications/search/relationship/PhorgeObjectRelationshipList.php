<?php

final class PhorgeObjectRelationshipList extends Phobject {

  private $viewer;
  private $object;
  private $relationships;

  public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer() {
    if ($this->viewer === null) {
      throw new PhutilInvalidStateException('setViewer');
    }

    return $this->viewer;
  }

  public function setObject($object) {
    $this->object = $object;
    return $this;
  }

  public function getObject() {
    if ($this->object === null) {
      throw new PhutilInvalidStateException('setObject');
    }

    return $this->object;
  }

  public function setRelationships(array $relationships) {
    assert_instances_of($relationships, 'PhorgeObjectRelationship');
    $this->relationships = $relationships;
    return $this;
  }

  public function getRelationships() {
    if ($this->relationships === null) {
      throw new PhutilInvalidStateException('setRelationships');
    }

    return $this->relationships;
  }

  public function newActionSubmenu(array $keys) {
    $object = $this->getObject();

    $actions = array();

    foreach ($keys as $key) {
      // If we're passed a menu item, just include it verbatim.
      if ($key instanceof PhorgeActionView) {
        $actions[] = $key;
        continue;
      }

      $relationship = $this->getRelationship($key);
      if (!$relationship) {
        throw new Exception(
          pht(
            'No object relationship of type "%s" exists.',
            $key));
      }

      $actions[$key] = $relationship->newAction($object);
    }

    return $this->newMenuWithActions($actions);
  }

  public function newActionMenu() {
    $relationships = $this->getRelationships();
    $object = $this->getObject();

    $actions = array();
    foreach ($relationships as $key => $relationship) {
      if (!$relationship->shouldAppearInActionMenu()) {
        continue;
      }

      $actions[$key] = $relationship->newAction($object);
    }

    if (!$actions) {
      return null;
    }

    $actions = msort($actions, 'getName');

    return $this->newMenuWithActions($actions)
      ->setName(pht('Edit Related Objects...'))
      ->setIcon('fa-link');
  }

  private function newMenuWithActions(array $actions) {
    $any_enabled = false;
    foreach ($actions as $action) {
      if (!$action->getDisabled()) {
        $any_enabled = true;
        break;
      }
    }

    return id(new PhorgeActionView())
      ->setDisabled(!$any_enabled)
      ->setSubmenu($actions);
  }

  public function getRelationship($key) {
    return idx($this->relationships, $key);
  }

  public static function newForObject(PhorgeUser $viewer, $object) {
    $relationships = PhorgeObjectRelationship::getAllRelationships();

    $results = array();
    foreach ($relationships as $key => $relationship) {
      $relationship = clone $relationship;

      $relationship->setViewer($viewer);
      if (!$relationship->isEnabledForObject($object)) {
        continue;
      }

      $source = $relationship->newSource();
      if (!$source->isEnabledForObject($object)) {
        continue;
      }

      $results[$key] = $relationship;
    }

    return id(new self())
      ->setViewer($viewer)
      ->setObject($object)
      ->setRelationships($results);
  }

}
