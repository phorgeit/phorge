<?php

final class AlmanacServiceSearchEngine
  extends PhabricatorApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Almanac Services');
  }

  public function getApplicationClassName() {
    return PhabricatorAlmanacApplication::class;
  }

  public function newQuery() {
    return new AlmanacServiceQuery();
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['match'] !== null) {
      $query->withNameNgrams($map['match']);
    }

    if ($map['names']) {
      $query->withNames($map['names']);
    }

    if ($map['devicePHIDs']) {
      $query->withDevicePHIDs($map['devicePHIDs']);
    }

    if ($map['serviceTypes']) {
      $query->withServiceTypes($map['serviceTypes']);
    }

    return $query;
  }


  protected function buildCustomSearchFields() {
    return array(
      id(new PhabricatorSearchTextField())
        ->setLabel(pht('Name Contains'))
        ->setKey('match')
        ->setDescription(pht('Search for services by name substring.')),
      id(new PhabricatorSearchStringListField())
        ->setLabel(pht('Exact Names'))
        ->setKey('names')
        ->setDescription(pht('Search for services with specific names.')),
      id(new PhabricatorSearchDatasourceField())
        ->setLabel(pht('Service Types'))
        ->setKey('serviceTypes')
        ->setDescription(pht('Find services by type.'))
        ->setDatasource(id(new AlmanacServiceTypeDatasource())),
      id(new PhabricatorPHIDsSearchField())
        ->setLabel(pht('Devices'))
        ->setKey('devicePHIDs')
        ->setDescription(
          pht('Search for services bound to particular devices.')),
    );
  }

  protected function getURI($path) {
    return '/almanac/service/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Services'),
    );

    return $names;
  }

  public function buildSavedQueryFromBuiltin($query_key) {

    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);

    switch ($query_key) {
      case 'all':
        return $query;
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function renderResultList(
    array $services,
    PhabricatorSavedQuery $query,
    array $handles) {
    assert_instances_of($services, 'AlmanacService');

    $viewer = $this->requireViewer();

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($services as $service) {
      $item = id(new PHUIObjectItemView())
        ->setObjectName(pht('Service %d', $service->getID()))
        ->setHeader($service->getName())
        ->setHref($service->getURI())
        ->setObject($service)
        ->addIcon(
          $service->getServiceImplementation()->getServiceTypeIcon(),
          $service->getServiceImplementation()->getServiceTypeShortName());

      $list->addItem($item);
    }

    $result = new PhabricatorApplicationSearchResultView();
    $result->setObjectList($list);
    $result->setNoDataString(pht('No Almanac Services found.'));

    return $result;
  }

  protected function getNewUserBody() {
    $see_devices = id(new PHUIButtonView())
      ->setTag('a')
      ->setText(pht('See Devices'))
      ->setHref('/almanac/device/');

    $create_button = id(new PHUIButtonView())
      ->setTag('a')
      ->setText(pht('Create a Service'))
      ->setHref('/almanac/service/edit/')
      ->setIcon('fa-plus')
      ->setColor(PHUIButtonView::GREEN);


    $app_name = pht('Services');
    $view = id(new PHUIBigInfoView())
      ->setIcon('fa-plug')
      ->setTitle(pht('Welcome to %s', $app_name))
      ->setDescription(
        pht(
          'Services describe pools of devices, and '.
          'they are available to Drydock for CI/CD, and more.'))
      ->addAction($see_devices)
      ->addAction($create_button);

      return $view;
  }
}
