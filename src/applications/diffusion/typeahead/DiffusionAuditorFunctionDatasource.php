<?php

final class DiffusionAuditorFunctionDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Auditors');
  }

  public function getPlaceholderText() {
    return pht('Type a user, project, package name or function...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgeProjectOrUserFunctionDatasource(),
      new PhorgeOwnersPackageFunctionDatasource(),
    );
  }

}
