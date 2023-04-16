<?php

/**
 * Triggers an event exactly once, at a specific epoch time.
 */
final class PhorgeOneTimeTriggerClock
  extends PhorgeTriggerClock {

  public function validateProperties(array $properties) {
    PhutilTypeSpec::checkMap(
      $properties,
      array(
        'epoch' => 'int',
      ));
  }

  public function getNextEventEpoch($last_epoch, $is_reschedule) {
    if ($last_epoch) {
      return null;
    }

    return $this->getProperty('epoch');
  }

}
