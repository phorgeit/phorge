<?php

final class PhorgeCalendarEventDefaultEditCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'calendar.event.default.edit';

  public function getCapabilityName() {
    return pht('Default Edit Policy');
  }

}
