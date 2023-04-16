<?php

final class NuanceItemSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getApplicationClassName() {
    return 'PhorgeNuanceApplication';
  }

  public function getResultTypeDescription() {
    return pht('Nuance Items');
  }

  public function newQuery() {
    return new NuanceItemQuery();
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    return $query;
  }

  protected function buildCustomSearchFields() {
    return array(
    );
  }

  protected function getURI($path) {
    return '/nuance/item/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'all' => pht('All Items'),
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
    array $items,
    PhorgeSavedQuery $query,
    array $handles) {
    assert_instances_of($items, 'NuanceItem');

    $viewer = $this->requireViewer();

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($items as $item) {
      $impl = $item->getImplementation();

      $view = id(new PHUIObjectItemView())
        ->setObjectName(pht('Item %d', $item->getID()))
        ->setHeader($item->getDisplayName())
        ->setHref($item->getURI());

      $view->addIcon(
        $impl->getItemTypeDisplayIcon(),
        $impl->getItemTypeDisplayName());

      $queue = $item->getQueue();
      if ($queue) {
        $view->addAttribute(
          phutil_tag(
            'a',
            array(
              'href' => $queue->getURI(),
            ),
            $queue->getName()));
      } else {
        $view->addAttribute(phutil_tag('em', array(), pht('Not in Queue')));
      }

      $list->addItem($view);
    }

    $result = new PhorgeApplicationSearchResultView();
    $result->setObjectList($list);
    $result->setNoDataString(pht('No items found.'));

    return $result;
  }

}
