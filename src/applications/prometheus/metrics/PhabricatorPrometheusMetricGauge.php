<?php

use Prometheus\CollectorRegistry;

/**
 * @phutil-external-symbol class CollectorRegistry
 */
abstract class PhabricatorPrometheusMetricGauge extends PhabricatorPrometheusMetric {

  final public function register(CollectorRegistry $registry): void {
    $this->metric = $registry->getOrRegisterGauge(
      self::METRIC_NAMESPACE,
      $this->getName(),
      $this->getHelp(),
      $this->getLabels());
  }

  final public function observe(float $value, array $labels): void {
    $this->metric->set($value, $labels);
  }
}
