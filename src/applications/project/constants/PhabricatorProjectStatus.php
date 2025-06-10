<?php

final class PhabricatorProjectStatus extends Phobject {

  const STATUS_ACTIVE       = 0;
  const STATUS_ARCHIVED     = 100;

  const STATUS_ACTIVE_KEY   = 'active';
  const STATUS_ARCHIVED_KEY = 'archived';

  public static function getNameForStatus($status) {
    $map = array(
      self::STATUS_ACTIVE     => pht('Active'),
      self::STATUS_ARCHIVED   => pht('Archived'),
    );

    return idx($map, coalesce($status, '?'), pht('Unknown'));
  }

  public static function getStatusMap() {
    return array(
      self::STATUS_ACTIVE   => pht('Active'),
      self::STATUS_ARCHIVED => pht('Archived'),
    );
  }

  public static function getStatusKeys() {
    return array(
      self::STATUS_ACTIVE_KEY => self::STATUS_ACTIVE,
      self::STATUS_ARCHIVED_KEY => self::STATUS_ARCHIVED,
    );
  }

  public static function getKeyForStatus(int $status) {
    return array_flip(self::getStatusKeys())[$status];
  }
}
