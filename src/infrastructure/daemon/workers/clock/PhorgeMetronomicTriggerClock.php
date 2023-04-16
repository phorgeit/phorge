<?php

/**
 * Triggers an event repeatedly, delaying a fixed number of seconds between
 * triggers.
 *
 * For example, this clock can trigger an event every 30 seconds.
 */
final class PhorgeMetronomicTriggerClock extends PhorgeTriggerClock {

  public function validateProperties(array $properties) {
    PhutilTypeSpec::checkMap(
      $properties,
      array(
        'period' => 'int',
      ));
  }

  public function getNextEventEpoch($last_epoch, $is_reschedule) {
    $period = $this->getProperty('period');

    if ($last_epoch) {
      $next = $last_epoch + $period;
      $next = max($next, $last_epoch + 1);
    } else {
      $next = PhorgeTime::getNow() + $period;
    }

    return $next;
  }

}
