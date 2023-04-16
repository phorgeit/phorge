<?php

final class PhorgeCalendarEventDefaultViewCapability
  extends PhorgePolicyCapability {

  const CAPABILITY = 'calendar.event.default.view';

  public function getCapabilityName() {
    return pht('Default View Policy');
  }

  public function shouldAllowPublicPolicySetting() {
    return true;
  }

}
