<?php

final class PHUIHomeView
  extends AphrontTagView {

  protected function getTagName() {
    return null;
  }

  protected function getTagAttributes() {
    return array();
  }

  protected function getTagContent() {
    require_celerity_resource('phorge-dashboard-css');
    $viewer = $this->getViewer();

    $has_maniphest = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeManiphestApplication',
      $viewer);

    $has_diffusion = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeDiffusionApplication',
      $viewer);

    $has_differential = PhorgeApplication::isClassInstalledForViewer(
      'PhorgeDifferentialApplication',
      $viewer);

    $revision_panel = null;
    if ($has_differential) {
      $revision_panel = $this->buildRevisionPanel();
    }

    $tasks_panel = null;
    if ($has_maniphest) {
      $tasks_panel = $this->buildTasksPanel();
    }

    $repository_panel = null;
    if ($has_diffusion) {
      $repository_panel = $this->buildRepositoryPanel();
    }

    $feed_panel = $this->buildFeedPanel();

    $dashboard = id(new AphrontMultiColumnView())
      ->setFluidlayout(true)
      ->setGutter(AphrontMultiColumnView::GUTTER_LARGE);

    $main_panel = phutil_tag(
      'div',
      array(
        'class' => 'homepage-panel',
      ),
      array(
        $revision_panel,
        $tasks_panel,
        $repository_panel,
      ));
    $dashboard->addColumn($main_panel, 'thirds');

    $side_panel = phutil_tag(
      'div',
      array(
        'class' => 'homepage-side-panel',
      ),
      array(
        $feed_panel,
      ));
    $dashboard->addColumn($side_panel, 'third');

      $view = id(new PHUIBoxView())
        ->addClass('dashboard-view')
        ->appendChild($dashboard);

      return $view;
  }

  private function buildRevisionPanel() {
    $viewer = $this->getViewer();
    if (!$viewer->isLoggedIn()) {
      return null;
    }

    $panel = $this->newQueryPanel()
      ->setName(pht('Active Revisions'))
      ->setProperty('class', 'DifferentialRevisionSearchEngine')
      ->setProperty('key', 'active');

    return $this->renderPanel($panel);
  }

  private function buildTasksPanel() {
    $viewer = $this->getViewer();

    if ($viewer->isLoggedIn()) {
      $name = pht('Assigned Tasks');
      $query = 'assigned';
    } else {
      $name = pht('Open Tasks');
      $query = 'open';
    }

    $panel = $this->newQueryPanel()
      ->setName($name)
      ->setProperty('class', 'ManiphestTaskSearchEngine')
      ->setProperty('key', $query)
      ->setProperty('limit', 15);

    return $this->renderPanel($panel);
  }

  public function buildFeedPanel() {
    $panel = $this->newQueryPanel()
      ->setName(pht('Recent Activity'))
      ->setProperty('class', 'PhorgeFeedSearchEngine')
      ->setProperty('key', 'all')
      ->setProperty('limit', 40);

    return $this->renderPanel($panel);
  }

  public function buildRepositoryPanel() {
    $panel = $this->newQueryPanel()
      ->setName(pht('Active Repositories'))
      ->setProperty('class', 'PhorgeRepositorySearchEngine')
      ->setProperty('key', 'active')
      ->setProperty('limit', 5);

    return $this->renderPanel($panel);
  }

  private function newQueryPanel() {
    $panel_type = id(new PhorgeDashboardQueryPanelType())
      ->getPanelTypeKey();

    return id(new PhorgeDashboardPanel())
      ->setPanelType($panel_type);
  }

  private function renderPanel(PhorgeDashboardPanel $panel) {
    $viewer = $this->getViewer();

    return id(new PhorgeDashboardPanelRenderingEngine())
      ->setViewer($viewer)
      ->setPanel($panel)
      ->setParentPanelPHIDs(array())
      ->renderPanel();
  }

}
