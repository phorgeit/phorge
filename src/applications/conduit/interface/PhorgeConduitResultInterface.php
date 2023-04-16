<?php

interface PhorgeConduitResultInterface
  extends PhorgePHIDInterface {

  public function getFieldSpecificationsForConduit();
  public function getFieldValuesForConduit();
  public function getConduitSearchAttachments();

}

// TEMPLATE IMPLEMENTATION /////////////////////////////////////////////////////

/* -(  PhorgeConduitResultInterface  )---------------------------------- */
/*

  public function getFieldSpecificationsForConduit() {
    return array(
      id(new PhorgeConduitSearchFieldSpecification())
        ->setKey('name')
        ->setType('string')
        ->setDescription(pht('The name of the object.')),
    );
  }

  public function getFieldValuesForConduit() {
    return array(
      'name' => $this->getName(),
    );
  }

  public function getConduitSearchAttachments() {
    return array();
  }

*/
