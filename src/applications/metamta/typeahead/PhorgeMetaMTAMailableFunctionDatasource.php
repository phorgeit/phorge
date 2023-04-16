<?php

final class PhorgeMetaMTAMailableFunctionDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Subscribers');
  }

  public function getPlaceholderText() {
    return pht(
      'Type a username, project, mailing list, package, or function...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeMetaMTAApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgeViewerDatasource(),
      new PhorgePeopleDatasource(),
      new PhorgeProjectMembersDatasource(),
      new PhorgeProjectDatasource(),
      new PhorgeOwnersPackageDatasource(),
      new PhorgeOwnersPackageOwnerDatasource(),
    );
  }

}
