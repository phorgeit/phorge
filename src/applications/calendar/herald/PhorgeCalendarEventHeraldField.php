<?php

abstract class PhorgeCalendarEventHeraldField extends HeraldField {

  public function supportsObject($object) {
    return ($object instanceof PhorgeCalendarEvent);
  }

  public function getFieldGroupKey() {
    return PhorgeCalendarEventHeraldFieldGroup::FIELDGROUPKEY;
  }

}
