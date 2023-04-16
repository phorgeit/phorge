<?php

final class PhrictionSchemaSpec extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhrictionDocument());
  }

}
