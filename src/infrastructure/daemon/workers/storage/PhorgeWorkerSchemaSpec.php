<?php

final class PhorgeWorkerSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeWorkerBulkJob());
  }

}
