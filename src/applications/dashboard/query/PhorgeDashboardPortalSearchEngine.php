<?php

final class PhorgeDashboardPortalSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Portals');
  }

  public function getApplicationClassName() {
    return 'PhorgeDashboardApplication';
  }

  public function newQuery() {
    return new PhorgeDashboardPortalQuery();
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();
    return $query;
  }

  protected function buildCustomSearchFields() {
    return array();
  }

  protected function getURI($path) {
    return '/portal/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array();

    $names['all'] = pht('All Portals');

    return $names;
  }

  public function buildSavedQueryFromBuiltin($query_key) {
    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);
    $viewer = $this->requireViewer();

    switch ($query_key) {
      case 'all':
        return $query;
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function renderResultList(
    array $portals,
    PhorgeSavedQuery $query,
    array $handles) {

    assert_instances_of($portals, 'PhorgeDashboardPortal');

    $viewer = $this->requireViewer();

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($portals as $portal) {
      $item = id(new PHUIObjectItemView())
        ->setObjectName($portal->getObjectName())
        ->setHeader($portal->getName())
        ->setHref($portal->getURI())
        ->setObject($portal);

      $list->addItem($item);
    }

    return id(new PhorgeApplicationSearchResultView())
      ->setObjectList($list)
      ->setNoDataString(pht('No portals found.'));
  }

}
