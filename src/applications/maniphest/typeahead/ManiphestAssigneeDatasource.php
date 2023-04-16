<?php

final class ManiphestAssigneeDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Assignees');
  }

  public function getPlaceholderText() {
    return pht('Type a username or "none"...');
  }

  public function getComponentDatasources() {
    return array(
      new PhorgePeopleDatasource(),
      new PhorgePeopleNoOwnerDatasource(),
    );
  }

}
