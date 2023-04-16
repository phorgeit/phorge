<?php

final class PhorgeProjectSearchField
  extends PhorgeSearchTokenizerField {

  protected function getDefaultValue() {
    return array();
  }

  protected function newDatasource() {
    return new PhorgeProjectLogicalDatasource();
  }

  protected function getValueFromRequest(AphrontRequest $request, $key) {
    $list = $this->getListFromRequest($request, $key);

    $phids = array();
    $slugs = array();
    $project_type = PhorgeProjectProjectPHIDType::TYPECONST;
    foreach ($list as $item) {
      $type = phid_get_type($item);
      if ($type == $project_type) {
        $phids[] = $item;
      } else {
        if (PhorgeTypeaheadDatasource::isFunctionToken($item)) {
          // If this is a function, pass it through unchanged; we'll evaluate
          // it later.
          $phids[] = $item;
        } else {
          $slugs[] = $item;
        }
      }
    }

    if ($slugs) {
      $projects = id(new PhorgeProjectQuery())
        ->setViewer($this->getViewer())
        ->withSlugs($slugs)
        ->execute();
      foreach ($projects as $project) {
        $phids[] = $project->getPHID();
      }
      $phids = array_unique($phids);
    }

    return $phids;

  }

  protected function newConduitParameterType() {
    return new ConduitProjectListParameterType();
  }

}
