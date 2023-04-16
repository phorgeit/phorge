<?php

final class PhorgeBadgesDatasource
  extends PhorgeTypeaheadDatasource {

  public function getBrowseTitle() {
    return pht('Browse Badges');
  }

  public function getPlaceholderText() {
    return pht('Type a badge name...');
  }

  public function getDatasourceApplicationClass() {
    return 'PhorgeBadgesApplication';
  }

  public function loadResults() {
    $viewer = $this->getViewer();
    $raw_query = $this->getRawQuery();

    $params = $this->getParameters();
    $recipient_phid = $params['recipientPHID'];

    $badges = id(new PhorgeBadgesQuery())
      ->setViewer($viewer)
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->execute();

    $awards = id(new PhorgeBadgesAwardQuery())
      ->setViewer($viewer)
      ->withAwarderPHIDs(array($viewer->getPHID()))
      ->withRecipientPHIDs(array($recipient_phid))
      ->execute();
    $awards = mpull($awards, null, 'getBadgePHID');

    $results = array();
    foreach ($badges as $badge) {
      $closed = null;

      $badge_awards = idx($awards, $badge->getPHID(), null);
      if ($badge_awards) {
        $closed = pht('Already awarded');
      }

      $status = $badge->getStatus();
      if ($status === PhorgeBadgesBadge::STATUS_ARCHIVED) {
        $closed = pht('Archived');
      }

      $results[] = id(new PhorgeTypeaheadResult())
        ->setName($badge->getName())
        ->setIcon($badge->getIcon())
        ->setColor(
          PhorgeBadgesQuality::getQualityColor($badge->getQuality()))
        ->setClosed($closed)
        ->setPHID($badge->getPHID());
    }

    $results = $this->filterResultsAgainstTokens($results);

    return $results;
  }

}
