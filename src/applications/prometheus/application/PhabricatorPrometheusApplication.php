<?php

use Prometheus\CollectorRegistry;
use Prometheus\Storage\Redis as RedisStorage;

final class PhabricatorPrometheusApplication extends PhabricatorApplication {

  private static $registry;

  public static function getRegistry(): CollectorRegistry {
    return self::$registry ?? (self::$registry = new CollectorRegistry(new RedisStorage()));
  }

  public function getName(): string {
    return pht('Prometheus');
  }

  public function getShortDescription(): string {
    return pht('Monitoring');
  }

  public function isLaunchable(): bool {
    return false;
  }

  public function canUninstall(): bool {
    return false;
  }

  public function getIcon(): string {
    return 'fa-heartbeat';
  }

  public function getApplicationGroup(): string {
    return self::GROUP_ADMIN;
  }

  public function getTitleGlyph(): string {
    return "\xE2\x99\xA5";
  }

  public function getOverview(): ?string {
    return null;
  }

  public function getRoutes(): array {
    return [
      '/metrics' => PhabricatorPrometheusMetricsController::class,
    ];
  }

  public function getFlavorText(): ?string {
    return null;
  }

}
