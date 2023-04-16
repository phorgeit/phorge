<?php

final class PhorgeCosChartFunction
  extends PhorgePureChartFunction {

  const FUNCTIONKEY = 'cos';

  protected function newArguments() {
    return array();
  }

  public function evaluateFunction(array $xv) {
    $yv = array();

    foreach ($xv as $x) {
      $yv[] = cos(deg2rad($x));
    }

    return $yv;
  }

}
