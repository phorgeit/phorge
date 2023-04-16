<?php

final class PhorgePeopleAvailabilitySearchEngineAttachment
  extends PhorgeSearchEngineAttachment {

  public function getAttachmentName() {
    return pht('User Availability');
  }

  public function getAttachmentDescription() {
    return pht('Get availability information for users.');
  }

  public function willLoadAttachmentData($query, $spec) {
    $query->needAvailability(true);
  }

  public function getAttachmentForObject($object, $data, $spec) {

    $until = $object->getAwayUntil();
    if ($until) {
      $until = (int)$until;
    } else {
      $until = null;
    }

    $value = $object->getDisplayAvailability();
    if ($value === null) {
      $value = PhorgeCalendarEventInvitee::AVAILABILITY_AVAILABLE;
    }

    $name = PhorgeCalendarEventInvitee::getAvailabilityName($value);
    $color = PhorgeCalendarEventInvitee::getAvailabilityColor($value);

    $event_phid = $object->getAvailabilityEventPHID();

    return array(
      'value' => $value,
      'until' => $until,
      'name' => $name,
      'color' => $color,
      'eventPHID' => $event_phid,
    );
  }

}
