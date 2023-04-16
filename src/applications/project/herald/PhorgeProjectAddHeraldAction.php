<?php

final class PhorgeProjectAddHeraldAction
  extends PhorgeProjectHeraldAction {

  const ACTIONCONST = 'projects.add';

  public function getHeraldActionName() {
    return pht('Add projects');
  }

  public function applyEffect($object, HeraldEffect $effect) {
    return $this->applyProjects($effect->getTarget(), $is_add = true);
  }

  public function getHeraldActionStandardType() {
    return self::STANDARD_PHID_LIST;
  }

  protected function getDatasource() {
    return new PhorgeProjectDatasource();
  }

  public function renderActionDescription($value) {
    return pht('Add projects: %s.', $this->renderHandleList($value));
  }

}
