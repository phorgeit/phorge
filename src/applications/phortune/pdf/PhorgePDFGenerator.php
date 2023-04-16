<?php

final class PhorgePDFGenerator
  extends Phobject {

  private $objects = array();
  private $hasIterator = false;

  private $infoObject;
  private $catalogObject;

  public function addObject(PhorgePDFObject $object) {
    if ($this->hasIterator) {
      throw new Exception(
        pht(
          'This generator has already emitted an iterator. You can not '.
          'modify the PDF document after you begin writing it.'));
    }

    $this->objects[] = $object;
    $index = count($this->objects);

    $object->setGenerator($this, $index);

    return $this;
  }

  public function getObjects() {
    return $this->objects;
  }

  public function newIterator() {
    $this->hasIterator = true;
    return id(new PhorgePDFIterator())
      ->setGenerator($this);
  }

  public function setInfoObject(PhorgePDFInfoObject $info_object) {
    $this->addObject($info_object);
    $this->infoObject = $info_object;
    return $this;
  }

  public function getInfoObject() {
    return $this->infoObject;
  }

  public function setCatalogObject(
    PhorgePDFCatalogObject $catalog_object) {
    $this->addObject($catalog_object);
    $this->catalogObject = $catalog_object;
    return $this;
  }

  public function getCatalogObject() {
    return $this->catalogObject;
  }

}
