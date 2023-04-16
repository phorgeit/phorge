<?php

abstract class PhorgeChartEngine
  extends Phobject {

  private $viewer;
  private $engineParameters = array();

  const KEY_ENGINE = 'engineKey';
  const KEY_PARAMETERS = 'engineParameters';

  final public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  final public function getViewer() {
    return $this->viewer;
  }

  final protected function setEngineParameter($key, $value) {
    $this->engineParameters[$key] = $value;
    return $this;
  }

  final protected function getEngineParameter($key, $default = null) {
    return idx($this->engineParameters, $key, $default);
  }

  final protected function getEngineParameters() {
    return $this->engineParameters;
  }

  final public static function newFromChart(PhorgeFactChart $chart) {
    $engine_key = $chart->getChartParameter(self::KEY_ENGINE);

    $engine_map = self::getAllChartEngines();
    if (!isset($engine_map[$engine_key])) {
      throw new Exception(
        pht(
          'Chart uses unknown engine key ("%s") and can not be rendered.',
          $engine_key));
    }

    return clone id($engine_map[$engine_key]);
  }

  final public static function getAllChartEngines() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getChartEngineKey')
      ->execute();
  }

  final public function getChartEngineKey() {
    return $this->getPhobjectClassConstant('CHARTENGINEKEY', 32);
  }

  final public function buildChart(PhorgeFactChart $chart) {
    $map = $chart->getChartParameter(self::KEY_PARAMETERS, array());
    return $this->newChart($chart, $map);
  }

  abstract protected function newChart(PhorgeFactChart $chart, array $map);

  final public function newStoredChart() {
    $viewer = $this->getViewer();

    $parameters = $this->getEngineParameters();

    $chart = id(new PhorgeFactChart())
      ->setChartParameter(self::KEY_ENGINE, $this->getChartEngineKey())
      ->setChartParameter(self::KEY_PARAMETERS, $this->getEngineParameters());

    $rendering_engine = id(new PhorgeChartRenderingEngine())
      ->setViewer($viewer)
      ->setChart($chart);

    return $rendering_engine->getStoredChart();
  }

  final public function buildChartPanel() {
    $chart = $this->newStoredChart();

    $panel_type = id(new PhorgeDashboardChartPanelType())
      ->getPanelTypeKey();

    $chart_panel = id(new PhorgeDashboardPanel())
      ->setPanelType($panel_type)
      ->setProperty('chartKey', $chart->getChartKey());

    return $chart_panel;
  }

  final protected function newFunction($name /* , ... */) {
    $argv = func_get_args();
    return id(new PhorgeComposeChartFunction())
      ->setArguments($argv);
  }

}
