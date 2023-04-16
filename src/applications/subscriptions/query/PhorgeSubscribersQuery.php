<?php

final class PhorgeSubscribersQuery extends PhorgeQuery {

  private $objectPHIDs;
  private $subscriberPHIDs;

  public static function loadSubscribersForPHID($phid) {
    if (!$phid) {
      return array();
    }

    $subscribers = id(new PhorgeSubscribersQuery())
      ->withObjectPHIDs(array($phid))
      ->execute();
    return $subscribers[$phid];
  }

  public function withObjectPHIDs(array $object_phids) {
    $this->objectPHIDs = $object_phids;
    return $this;
  }

  public function withSubscriberPHIDs(array $subscriber_phids) {
    $this->subscriberPHIDs = $subscriber_phids;
    return $this;
  }

  public function execute() {
    $query = new PhorgeEdgeQuery();

    $edge_type = PhorgeObjectHasSubscriberEdgeType::EDGECONST;

    $query->withSourcePHIDs($this->objectPHIDs);
    $query->withEdgeTypes(array($edge_type));

    if ($this->subscriberPHIDs) {
      $query->withDestinationPHIDs($this->subscriberPHIDs);
    }

    $edges = $query->execute();

    $results = array_fill_keys($this->objectPHIDs, array());
    foreach ($edges as $src => $edge_types) {
      foreach ($edge_types[$edge_type] as $dst => $data) {
        $results[$src][] = $dst;
      }
    }

    return $results;
  }
}
