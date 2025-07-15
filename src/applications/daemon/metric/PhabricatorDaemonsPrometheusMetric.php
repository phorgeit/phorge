<?php

final class PhabricatorDaemonsPrometheusMetric extends PhabricatorPrometheusMetricGauge {

  public function getName(): string {
    return 'daemons_total';
  }

  public function getHelp(): string {
    return 'The current count of phabricator daemon processes';
  }

  public function getLabels(): array {
    return ['class', 'status'];
  }

  public function getValues(): array {
    $device = AlmanacKeys::getLiveDevice();
    $table = new PhabricatorDaemonLog();
    $conn_r = $table->establishConnection('r');

    $data = queryfx_all(
      $conn_r,
      'SELECT daemon AS class, status, COUNT(*) AS count FROM %T WHERE host = %s GROUP BY daemon, status',
      $table->getTableName(),
      $device->getName()
    );

    return array_map(
      function (array $row): array {
        return [
          $row['count'],
          [
            'class'  => $row['class'],
            'status' => $row['status'],
          ],
        ];
      },
      $data);
  }

}
