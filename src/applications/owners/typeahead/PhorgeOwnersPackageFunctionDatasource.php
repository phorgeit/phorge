<?php

final class PhorgeOwnersPackageFunctionDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Packages');
  }

  public function getPlaceholderText() {
    return pht('Type a package name or function...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeOwnersApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgeOwnersPackageDatasource(),
      new PhorgeOwnersPackageOwnerDatasource(),
    );
  }

}
