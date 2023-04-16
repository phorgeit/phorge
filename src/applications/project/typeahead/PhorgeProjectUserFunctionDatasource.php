<?php

final class PhorgeProjectUserFunctionDatasource
  extends PhorgeTypeaheadCompositeDatasource {

  public function getBrowseTitle() {
    return pht('Browse User Projects');
  }

  public function getPlaceholderText() {
    return pht('Type projects(<user>)...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeProjectApplication';
  }

  public function getComponentDatasources() {
    return array(
      new PhorgeProjectLogicalUserDatasource(),
    );
  }

  protected function evaluateFunction($function, array $argv_list) {
    $result = parent::evaluateFunction($function, $argv_list);

    foreach ($result as $k => $v) {
      if ($v instanceof PhorgeQueryConstraint) {
        $result[$k] = $v->getValue();
      }
    }

    return $result;
  }

}
