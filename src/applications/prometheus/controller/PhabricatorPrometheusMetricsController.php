<?php

use Prometheus\RenderTextFormat;

/**
 * @phutil-external-symbol class CollectorRegistry
 * @phutil-external-symbol class InMemoryStorage
 * @phutil-external-symbol class RenderTextFormat
 */
final class PhabricatorPrometheusMetricsController extends PhabricatorController {

  private $registry;

  public function shouldRequireLogin(): bool {
    return false;
  }

  public function willProcessRequest(array $uri_data): void {
    $registry = PhabricatorPrometheusApplication::getRegistry();
    $metrics  = PhabricatorPrometheusMetric::getAllMetrics();

    foreach ($metrics as $metric) {
      $metric->register($registry);
      $metric->observeAll();
    }

    $this->registry = $registry;
  }

  public function processRequest(): AphrontResponse {
    $metrics  = $this->registry->getMetricFamilySamples();
    $renderer = new RenderTextFormat();

    return (new AphrontPlainTextResponse())
      ->setContent($renderer->render($metrics));
  }

}
