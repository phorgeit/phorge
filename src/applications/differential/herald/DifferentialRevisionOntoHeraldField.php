<?php

final class DifferentialRevisionOntoHeraldField
  extends DifferentialRevisionHeraldField {

  const FIELDCONST = 'differential.revision.onto';

  public function getHeraldFieldName() {
    return pht('Onto branch');
  }

  public function getHeraldFieldValue($object) {
    return $this->getAdapter()->getOntoBranch();
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_TEXT;
  }

}
