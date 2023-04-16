<?php

final class PhorgeCalendarSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeCalendarEvent());
  }

}
