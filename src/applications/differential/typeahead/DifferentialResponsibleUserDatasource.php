<?php

final class DifferentialResponsibleUserDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse Users');
  }

  public function getPlaceholderText() {
    return pht('Type a user name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeDifferentialApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgePeopleDatasource(),
    );
  }

  protected function evaluateValues(array $values) {
    return DifferentialResponsibleDatasource::expandResponsibleUsers(
      $this->getViewer(),
      $values);
  }

}
