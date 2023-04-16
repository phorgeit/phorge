<?php

final class PhorgeCustomFieldApplicationSearchDatasource
  extends PhorgeTypeaheadProxyDatasource {

  public function getComponentDatasources() {
    $datasources = parent::getComponentDatasources();

    $datasources[] =
      new PhorgeCustomFieldApplicationSearchAnyFunctionDatasource();
    $datasources[] =
      new PhorgeCustomFieldApplicationSearchNoneFunctionDatasource();

    return $datasources;
  }

}
