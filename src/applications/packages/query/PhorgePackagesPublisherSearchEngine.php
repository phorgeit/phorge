<?php

final class PhorgePackagesPublisherSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Package Publishers');
  }

  public function getApplicationClassName() {
    return 'PhorgePackagesApplication';
  }

  public function newQuery() {
    return id(new PhorgePackagesPublisherQuery());
  }

  public function canUseInPanelContext() {
    return false;
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['match'] !== null) {
      $query->withNameNgrams($map['match']);
    }

    return $query;
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhorgeSearchTextField())
        ->setLabel(pht('Name Contains'))
        ->setKey('match')
        ->setDescription(pht('Search for publishers by name substring.')),
    );
  }

  protected function getURI($path) {
    return '/packages/publisher/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Publishers'),
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
    array $publishers,
    PhorgeSavedQuery $query,
    array $handles) {

    assert_instances_of($publishers, 'PhorgePackagesPublisher');

    $viewer = $this->requireViewer();

    $list = id(new PhorgePackagesPublisherListView())
      ->setViewer($viewer)
      ->setPublishers($publishers)
      ->newListView();

    return id(new PhorgeApplicationSearchResultView())
      ->setObjectList($list)
      ->setNoDataString(pht('No publishers found.'));
  }

}
