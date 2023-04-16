<?php

final class PhorgeProjectOrUserFunctionDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Users and Projects');
  }

  public function getPlaceholderText() {
    return pht('Type a user, project name, or function...');
  }

  public function getComponentDatasources() {
    return array(
      new PhorgeViewerDatasource(),
      new PhorgePeopleDatasource(),
      new PhorgeProjectDatasource(),
      new PhorgeProjectMembersDatasource(),
      new PhorgeProjectUserFunctionDatasource(),
    );
  }


}
