<?php

final class PhorgeTypeaheadRuntimeCompositeDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  private $datasources = array();

  public function getComponentDatasources() {
    return $this->datasources;
  }

  public function getPlaceholderText() {
    throw new Exception(pht('This source is not usable directly.'));
  }

  public function addDatasource(PhorgeTypeaheadDatasource $source) {
    $this->datasources[] = $source;
    return $this;
  }

}
