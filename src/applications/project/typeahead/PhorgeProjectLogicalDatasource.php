<?php

final class PhorgeProjectLogicalDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Projects');
  }

  public function getPlaceholderText() {
    return pht('Type a project name or function...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeProjectApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgeProjectNoProjectsDatasource(),
      new PhorgeProjectLogicalAncestorDatasource(),
      new PhorgeProjectLogicalOrNotDatasource(),
      new PhorgeProjectLogicalViewerDatasource(),
      new PhorgeProjectLogicalOnlyDatasource(),
      new PhorgeProjectLogicalUserDatasource(),
    );
  }

}
