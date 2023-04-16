<?php

final class PhorgeDashboardChartPanelType
  extends PhorgeDashboardPanelType {

  public function getPanelTypeKey() {
    return 'chart';
  }

  public function getPanelTypeName() {
    return pht('Chart Panel');
  }

  public function getIcon() {
    return 'fa-area-chart';
  }

  public function getPanelTypeDescription() {
    return pht('Show a chart.');
  }

  protected function newEditEngineFields(PhorgeDashboardPanel $panel) {
    $chart_field = id(new PhorgeTextEditField())
      ->setKey('chartKey')
      ->setLabel(pht('Chart'))
      ->setTransactionType(
        PhorgeDashboardChartPanelChartTransaction::TRANSACTIONTYPE)
      ->setValue($panel->getProperty('chartKey', ''));

    return array(
      $chart_field,
    );
  }

  public function renderPanelContent(
    PhorgeUser $viewer,
    PhorgeDashboardPanel $panel,
    PhorgeDashboardPanelRenderingEngine $engine) {

    $engine = id(new PhorgeChartRenderingEngine())
      ->setViewer($viewer);

    $chart = $engine->loadChart($panel->getProperty('chartKey'));
    if (!$chart) {
      return pht('no such chart!');
    }

    return $engine->newChartView();
  }

  public function adjustPanelHeader(
    PhorgeUser $viewer,
    PhorgeDashboardPanel $panel,
    PhorgeDashboardPanelRenderingEngine $engine,
    PHUIHeaderView $header) {

    $key = $panel->getProperty('chartKey');
    $uri = PhorgeChartRenderingEngine::getChartURI($key);

    $icon = id(new PHUIIconView())
      ->setIcon('fa-area-chart');

    $button = id(new PHUIButtonView())
      ->setTag('a')
      ->setText(pht('View Chart'))
      ->setIcon($icon)
      ->setHref($uri)
      ->setColor(PHUIButtonView::GREY);

    $header->addActionLink($button);

    return $header;
  }


}
