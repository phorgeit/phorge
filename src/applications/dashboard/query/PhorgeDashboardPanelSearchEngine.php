<?php

final class PhorgeDashboardPanelSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Dashboard Panels');
  }

  public function getApplicationClassName() {
    return 'PhorgeDashboardApplication';
  }

  public function newQuery() {
    return new PhorgeDashboardPanelQuery();
  }

  public function canUseInPanelContext() {
    return false;
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();
    if ($map['status']) {
      switch ($map['status']) {
        case 'active':
          $query->withArchived(false);
          break;
        case 'archived':
          $query->withArchived(true);
          break;
        default:
          break;
      }
    }

    if ($map['paneltype']) {
      $query->withPanelTypes(array($map['paneltype']));
    }

    if ($map['authorPHIDs']) {
      $query->withAuthorPHIDs($map['authorPHIDs']);
    }

    return $query;
  }

  protected function buildCustomSearchFields() {

    return array(
        id(new PhorgeSearchDatasourceField())
          ->setLabel(pht('Authored By'))
          ->setKey('authorPHIDs')
          ->setDatasource(new PhorgePeopleUserFunctionDatasource()),
        id(new PhorgeSearchSelectField())
          ->setKey('status')
          ->setLabel(pht('Status'))
          ->setOptions(
            id(new PhorgeDashboardPanel())
              ->getStatuses()),
        id(new PhorgeSearchSelectField())
          ->setKey('paneltype')
          ->setLabel(pht('Panel Type'))
          ->setOptions(
            id(new PhorgeDashboardPanel())
              ->getPanelTypes()),
    );
  }

  protected function getURI($path) {
    return '/dashboard/panel/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array();

    if ($this->requireViewer()->isLoggedIn()) {
      $names['authored'] = pht('Authored');
    }

    $names['active'] = pht('Active Panels');
    $names['all'] = pht('All Panels');

    return $names;
  }

  public function buildSavedQueryFromBuiltin($query_key) {
    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);
    $viewer = $this->requireViewer();

    switch ($query_key) {
      case 'active':
        return $query->setParameter('status', 'active');
      case 'authored':
        return $query->setParameter(
          'authorPHIDs',
          array(
            $viewer->getPHID(),
          ));
      case 'all':
        return $query;
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function renderResultList(
    array $panels,
    PhorgeSavedQuery $query,
    array $handles) {

    $viewer = $this->requireViewer();

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($panels as $panel) {
      $item = id(new PHUIObjectItemView())
        ->setObjectName($panel->getMonogram())
        ->setHeader($panel->getName())
        ->setHref('/'.$panel->getMonogram())
        ->setObject($panel);

      $impl = $panel->getImplementation();
      if ($impl) {
        $type_text = $impl->getPanelTypeName();
      } else {
        $type_text = nonempty($panel->getPanelType(), pht('Unknown Type'));
      }
      $item->addAttribute($type_text);

      $properties = $panel->getProperties();
      $class = idx($properties, 'class');
      $item->addAttribute($class);

      if ($panel->getIsArchived()) {
        $item->setDisabled(true);
      }

      $list->addItem($item);
    }

    $result = new PhorgeApplicationSearchResultView();
    $result->setObjectList($list);
    $result->setNoDataString(pht('No panels found.'));

    return $result;
  }

}
