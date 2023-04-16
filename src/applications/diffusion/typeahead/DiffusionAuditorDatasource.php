<?php

final class DiffusionAuditorDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Auditors');
  }

  public function getPlaceholderText() {
    return pht('Type a user, project or package name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDiffusionApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgePeopleDatasource(),
      new PhorgeProjectDatasource(),
      new PhorgeOwnersPackageDatasource(),
    );
  }

}
