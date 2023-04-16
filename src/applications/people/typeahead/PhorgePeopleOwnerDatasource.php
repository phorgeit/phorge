<?php

final class PhorgePeopleOwnerDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Owners');
  }

  public function getPlaceholderText() {
    return pht('Type a username or function...');
  }

  public function getComponentDatasources() {
    return array(
      new PhorgeViewerDatasource(),
      new PhorgePeopleNoOwnerDatasource(),
      new PhorgePeopleAnyOwnerDatasource(),
      new PhorgePeopleDatasource(),
      new PhorgeProjectMembersDatasource(),
    );
  }

}
