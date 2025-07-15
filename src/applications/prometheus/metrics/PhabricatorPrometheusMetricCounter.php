<?php

use Prometheus\CollectorRegistry;

/**
 * @phutil-external-symbol class CollectorRegistry
 */
abstract class PhabricatorPrometheusMetricCounter extends PhabricatorPrometheusMetric {

  final public function register(CollectorRegistry $registry): void {
    $this->metric = $registry->getOrRegisterCounter(
      self::METRIC_NAMESPACE,
      $this->getName(),
      $this->getHelp(),
      $this->getLabels());
  }

  final public function observe(float $value, array $labels): void {
    $this->metric->incBy($value, $labels);
  }
}
