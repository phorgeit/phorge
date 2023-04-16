<?php

final class PHUIUserAvailabilityView
  extends AphrontTagView {

  private $user;

  public function setAvailableUser(PhorgeUser $user) {
    $this->user = $user;
    return $this;
  }

  public function getAvailableUser() {
    return $this->user;
  }

  protected function getTagContent() {
    $viewer = $this->getViewer();
    $user = $this->getAvailableUser();

    $until = $user->getAwayUntil();
    if (!$until) {
      return pht('Available');
    }

    $const = $user->getDisplayAvailability();
    $name = PhorgeCalendarEventInvitee::getAvailabilityName($const);
    $color = PhorgeCalendarEventInvitee::getAvailabilityColor($const);

    $away_tag = id(new PHUITagView())
      ->setType(PHUITagView::TYPE_SHADE)
      ->setColor($color)
      ->setName($name)
      ->setDotColor($color);

    $now = PhorgeTime::getNow();

    // Try to load the event handle. If it's invalid or the user can't see it,
    // we'll just render a generic message.
    $object_phid = $user->getAvailabilityEventPHID();
    $handle = null;
    if ($object_phid) {
      $handles = $viewer->loadHandles(array($object_phid));
      $handle = $handles[$object_phid];
      if (!$handle->isComplete() || $handle->getPolicyFiltered()) {
        $handle = null;
      }
    }

    switch ($const) {
      case PhorgeCalendarEventInvitee::AVAILABILITY_AWAY:
        if ($handle) {
          $description = pht(
            'Away at %s until %s.',
            $handle->renderLink(),
            $viewer->formatShortDateTime($until, $now));
        } else {
          $description = pht(
            'Away until %s.',
            $viewer->formatShortDateTime($until, $now));
        }
        break;
      case PhorgeCalendarEventInvitee::AVAILABILITY_BUSY:
      default:
        if ($handle) {
          $description = pht(
            'Busy at %s until %s.',
            $handle->renderLink(),
            $viewer->formatShortDateTime($until, $now));
        } else {
          $description = pht(
            'Busy until %s.',
            $viewer->formatShortDateTime($until, $now));
        }
        break;
    }

    return array(
      $away_tag,
      ' ',
      $description,
    );
  }

}
