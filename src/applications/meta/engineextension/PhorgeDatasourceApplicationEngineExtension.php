<?php

final class PhorgeDatasourceApplicationEngineExtension
  extends PhorgeDatasourceEngineExtension {

  public function newQuickSearchDatasources() {
    return array(
      new PhorgeApplicationDatasource(),
    );
  }
}
