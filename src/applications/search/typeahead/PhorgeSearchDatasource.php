<?php

final class PhorgeSearchDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Results');
  }

  public function getPlaceholderText() {
    return pht('Type an object name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeSearchApplication';
  }

  public function getComponentDatasources() {
    $sources = id(new PhorgeDatasourceEngine())
      ->getAllQuickSearchDatasources();

    // These results are always rendered in the full browse display mode, so
    // set the browse flag on all component sources.
    foreach ($sources as $source) {
      $source->setIsBrowse(true);
    }

    return $sources;
  }

}
