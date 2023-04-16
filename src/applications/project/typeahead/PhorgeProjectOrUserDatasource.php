<?php

final class PhorgeProjectOrUserDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Users and Projects');
  }

  public function getPlaceholderText() {
    return pht('Type a user or project name...');
  }

  public function getComponentDatasources() {
    return array(
      new PhorgePeopleDatasource(),
      new PhorgeProjectDatasource(),
    );
  }

}
