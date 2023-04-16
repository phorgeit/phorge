<?php

final class PhorgeCalendarEventInviteesPolicyRule
  extends PhorgePolicyRule {

  private $invited = array();
  private $sourcePHIDs = array();

  public function getObjectPolicyKey() {
    return 'calendar.event.invitees';
  }

  public function getObjectPolicyName() {
    return pht('Event Invitees');
  }

  public function getPolicyExplanation() {
    return pht('Users invited to this event can take this action.');
  }

  public function getRuleDescription() {
    return pht('event invitees');
  }

  public function canApplyToObject(PhorgePolicyInterface $object) {
    return ($object instanceof PhorgeCalendarEvent);
  }

  public function willApplyRules(
    PhorgeUser $viewer,
    array $values,
    array $objects) {

    $viewer_phid = $viewer->getPHID();
    if (!$viewer_phid) {
      return;
    }

    if (empty($this->invited[$viewer_phid])) {
      $this->invited[$viewer_phid] = array();
    }

    if (!isset($this->sourcePHIDs[$viewer_phid])) {
      $source_phids = PhorgeEdgeQuery::loadDestinationPHIDs(
        $viewer_phid,
        PhorgeProjectMaterializedMemberEdgeType::EDGECONST);
      $source_phids[] = $viewer_phid;
      $this->sourcePHIDs[$viewer_phid] = $source_phids;
    }

    foreach ($objects as $key => $object) {
      $cache = $this->getTransactionHint($object);
      if ($cache === null) {
        // We don't have a hint for this object, so we'll deal with it below.
        continue;
      }

      // We have a hint, so use that as the source of truth.
      unset($objects[$key]);

      foreach ($this->sourcePHIDs[$viewer_phid] as $source_phid) {
        if (isset($cache[$source_phid])) {
          $this->invited[$viewer_phid][$object->getPHID()] = true;
          break;
        }
      }
    }

    $phids = mpull($objects, 'getPHID');
    if (!$phids) {
      return;
    }

    $invited = id(new PhorgeCalendarEventInvitee())->loadAllWhere(
      'eventPHID IN (%Ls)
        AND inviteePHID IN (%Ls)
        AND status != %s',
      $phids,
      $this->sourcePHIDs[$viewer_phid],
      PhorgeCalendarEventInvitee::STATUS_UNINVITED);
    $invited = mpull($invited, 'getEventPHID');

    $this->invited[$viewer_phid] += array_fill_keys($invited, true);
  }

  public function applyRule(
    PhorgeUser $viewer,
    $value,
    PhorgePolicyInterface $object) {

    $viewer_phid = $viewer->getPHID();
    if (!$viewer_phid) {
      return false;
    }

    $invited = idx($this->invited, $viewer_phid);
    return isset($invited[$object->getPHID()]);
  }

  public function getValueControlType() {
    return self::CONTROL_TYPE_NONE;
  }

}
