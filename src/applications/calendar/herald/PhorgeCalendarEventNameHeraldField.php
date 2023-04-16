<?php

final class PhorgeCalendarEventNameHeraldField
  extends PhorgeCalendarEventHeraldField {

  const FIELDCONST = 'calendar.event.name';

  public function getHeraldFieldName() {
    return pht('Name');
  }

  public function getHeraldFieldValue($object) {
    return $object->getName();
  }

  protected function getHeraldFieldStandardType() {
    return self::STANDARD_TEXT;
  }

}
