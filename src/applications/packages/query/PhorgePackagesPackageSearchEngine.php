<?php

final class PhorgePackagesPackageSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Packages');
  }

  public function getApplicationClassName() {
    return 'PhorgePackagesApplication';
  }

  public function newQuery() {
    return id(new PhorgePackagesPackageQuery());
  }

  public function canUseInPanelContext() {
    return false;
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['match'] !== null) {
      $query->withNameNgrams($map['match']);
    }

    if ($map['publisherPHIDs']) {
      $query->withPublisherPHIDs($map['publisherPHIDs']);
    }

    return $query;
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhorgeSearchTextField())
        ->setLabel(pht('Name Contains'))
        ->setKey('match')
        ->setDescription(pht('Search for packages by name substring.')),
      id(new PhorgeSearchDatasourceField())
        ->setLabel(pht('Publishers'))
        ->setKey('publisherPHIDs')
        ->setAliases(array('publisherPHID', 'publisher', 'publishers'))
        ->setDatasource(new PhorgePackagesPublisherDatasource())
        ->setDescription(pht('Search for packages by publisher.')),
    );
  }

  protected function getURI($path) {
    return '/packages/package/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Packages'),
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
    array $packages,
    PhorgeSavedQuery $query,
    array $handles) {

    assert_instances_of($packages, 'PhorgePackagesPackage');
    $viewer = $this->requireViewer();

    $list = id(new PhorgePackagesPackageListView())
      ->setViewer($viewer)
      ->setPackages($packages)
      ->newListView();

    return id(new PhorgeApplicationSearchResultView())
      ->setObjectList($list)
      ->setNoDataString(pht('No packages found.'));
  }

}
