<?php

final class PhorgeCountdownSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeCountdown());
  }

}
