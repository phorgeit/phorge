<?php

final class PhorgeApplicationPanelController
  extends PhorgeApplicationsController {

  private $application;

  public function handleRequest(AphrontRequest $request) {
    $viewer = $this->getViewer();

    $application = $request->getURIData('application');
    $panel_key = $request->getURIData('panel');

    $selected = id(new PhorgeApplicationQuery())
      ->setViewer($viewer)
      ->withClasses(array($application))
      ->requireCapabilities(
        array(
          PhorgePolicyCapability::CAN_VIEW,
          PhorgePolicyCapability::CAN_EDIT,
        ))
      ->executeOne();
    if (!$selected) {
      return new Aphront404Response();
    }

    $panels =
      PhorgeApplicationConfigurationPanel::loadAllPanelsForApplication(
        $selected);
    if (empty($panels[$panel_key])) {
      return new Aphront404Response();
    }

    $panel = $panels[$panel_key];

    if (!$panel->shouldShowForApplication($selected)) {
      return new Aphront404Response();
    }

    $panel->setViewer($viewer);
    $panel->setApplication($selected);

    $this->application = $selected;

    return $panel->handlePanelRequest($request, $this);
  }

  public function buildPanelCrumbs(
    PhorgeApplicationConfigurationPanel $panel) {
    $application = $this->application;

    $crumbs = $this->buildApplicationCrumbs();

    $view_uri = '/applications/view/'.get_class($application).'/';
    $crumbs->addTextCrumb($application->getName(), $view_uri);

    return $crumbs;
  }

  public function buildPanelPage(
    PhorgeApplicationConfigurationPanel $panel,
    $title,
    $crumbs,
    $content) {

    return $this->newPage()
      ->setTitle($title)
      ->setCrumbs($crumbs)
      ->appendChild($content);
  }

}
