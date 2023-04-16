<?php

abstract class PhorgeDashboardPanelType extends Phobject {

  abstract public function getPanelTypeKey();
  abstract public function getPanelTypeName();
  abstract public function getPanelTypeDescription();
  abstract public function getIcon();

  abstract public function renderPanelContent(
    PhorgeUser $viewer,
    PhorgeDashboardPanel $panel,
    PhorgeDashboardPanelRenderingEngine $engine);

  /**
   * Should this panel pull content in over AJAX?
   *
   * Normally, panels use AJAX to render their content. This makes the page
   * interactable sooner, allows panels to render in parallel, and prevents one
   * slow panel from slowing everything down.
   *
   * However, some panels are very cheap to build (i.e., no expensive service
   * calls or complicated rendering). In these cases overall performance can be
   * improved by disabling async rendering so the panel rendering happens in the
   * same process.
   *
   * @return bool True to enable asynchronous rendering when appropriate.
   */
  public function shouldRenderAsync() {
    return true;
  }

  public function adjustPanelHeader(
    PhorgeUser $viewer,
    PhorgeDashboardPanel $panel,
    PhorgeDashboardPanelRenderingEngine $engine,
    PHUIHeaderView $header) {
    return $header;
  }

  public static function getAllPanelTypes() {
    return id(new PhutilClassMapQuery())
      ->setAncestorClass(__CLASS__)
      ->setUniqueMethod('getPanelTypeKey')
      ->execute();
  }

  final public function getEditEngineFields(PhorgeDashboardPanel $panel) {
    return $this->newEditEngineFields($panel);
  }

  abstract protected function newEditEngineFields(
    PhorgeDashboardPanel $panel);

  public function getSubpanelPHIDs(PhorgeDashboardPanel $panel) {
    return array();
  }

  public function newHeaderEditActions(
    PhorgeDashboardPanel $panel,
    PhorgeUser $viewer,
    $context_phid) {
    return array();
  }

}
