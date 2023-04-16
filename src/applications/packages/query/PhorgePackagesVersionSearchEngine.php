<?php

final class PhorgePackagesVersionSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Package Versions');
  }

  public function getApplicationClassName() {
    return 'PhorgePackagesApplication';
  }

  public function newQuery() {
    return id(new PhorgePackagesVersionQuery());
  }

  public function canUseInPanelContext() {
    return false;
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['match'] !== null) {
      $query->withNameNgrams($map['match']);
    }

    if ($map['packagePHIDs']) {
      $query->withPackagePHIDs($map['packagePHIDs']);
    }

    return $query;
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhorgeSearchTextField())
        ->setLabel(pht('Name Contains'))
        ->setKey('match')
        ->setDescription(pht('Search for versions by name substring.')),
      id(new PhorgeSearchDatasourceField())
        ->setLabel(pht('Packages'))
        ->setKey('packagePHIDs')
        ->setAliases(array('packagePHID', 'package', 'packages'))
        ->setDatasource(new PhorgePackagesPackageDatasource())
        ->setDescription(pht('Search for versions by package.')),
    );
  }
  protected function getURI($path) {
    return '/packages/version/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Versions'),
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
    array $versions,
    PhorgeSavedQuery $query,
    array $handles) {

    assert_instances_of($versions, 'PhorgePackagesVersion');
    $viewer = $this->requireViewer();

    $list = id(new PhorgePackagesVersionListView())
      ->setViewer($viewer)
      ->setVersions($versions)
      ->newListView();

    return id(new PhorgeApplicationSearchResultView())
      ->setObjectList($list)
      ->setNoDataString(pht('No versions found.'));
  }

}
