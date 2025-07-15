<?php

use Prometheus\CollectorRegistry;

/**
 * @phutil-external-symbol class CollectorRegistry
 */
abstract class PhabricatorPrometheusMetric extends Phobject {
  const METRIC_NAMESPACE = 'phabricator';

  protected $metric;

  abstract public function getName(): string;

  public function getHelp(): string {
    return '';
  }

  public function getLabels(): array {
    return [];
  }

  abstract public function getValues(): array;

  abstract public function observe(float $value, array $labels): void;

  abstract public function register(CollectorRegistry $registry): void;

  final public function observeAll(): void{
    foreach ($this->getValues() as $data) {
      if (!is_array($data) || count($data) === 1) {
        $value  = $data;
        $labels = [];
      } else if (count($data) === 2) {
        list($value, $labels) = $data;
      } else {
        throw new Exception(
          pht(
            'Value for "%s" metric is malformed.',
            $this->getName()));
      }

      $missing_labels    = array_diff($this->getLabels(), array_keys($labels));
      $unexpected_labels = array_diff(array_keys($labels), $this->getLabels());

      // Ensure that all predefined labels exist for this value.
      if (count($missing_labels) > 0) {
        throw new Exception(
          pht(
            'Value for "%s" metric is missing expected labels: %s',
            $this->getName(),
            implode(', ', $missing_labels)));
      }

      // Ensure that the data point doesn't contain any labels that weren't
      // predefined (returned from @{method:getLabels}).
      if (count($unexpected_labels) > 0) {
        throw new Exception(
          pht(
            'Value for "%s" metric has unexpected labels: %s',
            $this->getName(),
            implode(', ', $unexpected_labels)));
      }

      // We need to ensure that label values are passed to `$gauge->set` in the
      // same order as was returned by @{method:getLabels}.
      $labels = array_map(
        function (string $name) use ($labels) {
          return $labels[$name];
        },
	$this->getLabels());
      $this->observe($value, $labels);
    }
  }

  final public static function getAllMetrics(): array {
    return (new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getName')
      ->execute();
  }

}
