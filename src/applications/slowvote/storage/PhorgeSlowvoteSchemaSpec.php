<?php

final class PhorgeSlowvoteSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeSlowvotePoll());
  }

}
