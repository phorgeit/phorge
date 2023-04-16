<?php

final class PhorgeDatasourceEditField
  extends PhorgeTokenizerEditField {

  private $datasource;

  public function setDatasource(PhorgeTypeaheadDatasource $datasource) {
    $this->datasource = $datasource;
    return $this;
  }

  public function getDatasource() {
    if (!$this->datasource) {
      throw new PhutilInvalidStateException('setDatasource');
    }
    return $this->datasource;
  }

  protected function newDatasource() {
    return id(clone $this->getDatasource());
  }

}
