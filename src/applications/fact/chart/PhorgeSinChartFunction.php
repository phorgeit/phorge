<?php

final class PhorgeSinChartFunction
  extends PhorgePureChartFunction {

  const FUNCTIONKEY = 'sin';

  protected function newArguments() {
    return array();
  }

  public function evaluateFunction(array $xv) {
    $yv = array();

    foreach ($xv as $x) {
      $yv[] = sin(deg2rad($x));
    }

    return $yv;
  }

}
