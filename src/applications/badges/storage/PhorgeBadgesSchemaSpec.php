<?php

final class PhorgeBadgesSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeBadgesBadge());
  }

}
