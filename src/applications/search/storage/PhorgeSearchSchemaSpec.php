<?php

final class PhorgeSearchSchemaSpec
  extends PhorgeConfigSchemaSpec {

  public function buildSchemata() {
    $this->buildEdgeSchemata(new PhorgeProfileMenuItemConfiguration());

    $this->buildRawSchema(
      'search',
      PhorgeSearchDocument::STOPWORDS_TABLE,
      array(
        'value' => 'sort32',
      ),
      array());
  }

}
