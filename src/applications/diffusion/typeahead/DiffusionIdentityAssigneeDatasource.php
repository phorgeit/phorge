<?php

final class DiffusionIdentityAssigneeDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Assignee');
  }

  public function getPlaceholderText() {
    return pht('Type a username or function...');
  }

  public function getComponentDatasources() {
    return array(
      new PhorgePeopleDatasource(),
      new DiffusionIdentityUnassignedDatasource(),
    );
  }

}
