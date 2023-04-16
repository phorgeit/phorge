<?php

final class DifferentialReviewerDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Reviewers');
  }

  public function getPlaceholderText() {
    return pht('Type a user, project, or package name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDifferentialApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgePeopleDatasource(),
      new PhorgeProjectDatasource(),
      new PhorgeOwnersPackageDatasource(),
      new DifferentialBlockingReviewerDatasource(),
    );
  }

}
