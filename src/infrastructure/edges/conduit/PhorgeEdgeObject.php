<?php

final class PhorgeEdgeObject
  extends Phobject
  implements PhorgePolicyInterface {

  private $id;
  private $src;
  private $dst;
  private $type;
  private $dateCreated;
  private $sequence;

  public static function newFromRow(array $row) {
    $edge = new self();

    $edge->id = idx($row, 'id');
    $edge->src = idx($row, 'src');
    $edge->dst = idx($row, 'dst');
    $edge->type = idx($row, 'type');
    $edge->dateCreated = idx($row, 'dateCreated');
    $edge->sequence = idx($row, 'seq');

    return $edge;
  }

  public function getID() {
    return $this->id;
  }

  public function getSourcePHID() {
    return $this->src;
  }

  public function getEdgeType() {
    return $this->type;
  }

  public function getDestinationPHID() {
    return $this->dst;
  }

  public function getPHID() {
    return null;
  }

  public function getDateCreated() {
    return $this->dateCreated;
  }

  public function getSequence() {
    return $this->sequence;
  }


/* -(  PhorgePolicyInterface  )----------------------------------------- */


  public function getCapabilities() {
    return array(
      PhorgePolicyCapability::CAN_VIEW,
    );
  }

  public function getPolicy($capability) {
    switch ($capability) {
      case PhorgePolicyCapability::CAN_VIEW:
        return PhorgePolicies::getMostOpenPolicy();
    }
  }

  public function hasAutomaticCapability($capability, PhorgeUser $viewer) {
    return false;
  }

}
