<?php

final class PhorgeDatasourceEngine extends Phobject {

  private $viewer;

  public function setViewer(PhorgeUser $viewer) {
    $this->viewer = $viewer;
    return $this;
  }

  public function getViewer() {
    return $this->viewer;
  }

  public function getAllQuickSearchDatasources() {
    return PhorgeDatasourceEngineExtension::getAllQuickSearchDatasources();
  }

  public function newJumpURI($query) {
    $viewer = $this->getViewer();
    $extensions = PhorgeDatasourceEngineExtension::getAllExtensions();

    foreach ($extensions as $extension) {
      $jump_uri = id(clone $extension)
        ->setViewer($viewer)
        ->newJumpURI($query);

      if ($jump_uri !== null) {
        return $jump_uri;
      }
    }

    return null;
  }

  public function newDatasourcesForCompositeDatasource(
    PhorgeTypeaheadCompositeDatasource $datasource) {
    $viewer = $this->getViewer();
    $extensions = PhorgeDatasourceEngineExtension::getAllExtensions();

    $sources = array();
    foreach ($extensions as $extension) {
      $extension_sources = id(clone $extension)
        ->setViewer($viewer)
        ->newDatasourcesForCompositeDatasource($datasource);
      foreach ($extension_sources as $extension_source) {
        $sources[] = $extension_source;
      }
    }

    return $sources;
  }

}
