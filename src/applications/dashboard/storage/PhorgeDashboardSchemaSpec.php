<?php

final class PhorgeDashboardSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeDashboard());
  }

}
