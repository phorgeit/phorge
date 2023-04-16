<?php

final class PhorgePeopleUserFunctionDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Users');
  }

  public function getPlaceholderText() {
    return pht('Type a username or function...');
  }

  public function getComponentDatasources() {
    $sources = array(
      new PhorgeViewerDatasource(),
      new PhorgePeopleDatasource(),
      new PhorgeProjectMembersDatasource(),
    );

    return $sources;
  }

}
