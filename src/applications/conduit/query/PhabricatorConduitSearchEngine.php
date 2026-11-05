<?php

final class PhabricatorConduitSearchEngine
  extends PhabricatorApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Conduit Methods');
  }

  public function getApplicationClassName() {
    return PhabricatorConduitApplication::class;
  }

  public function canUseInPanelContext() {
    return false;
  }

  public function getPageSize(PhabricatorSavedQuery $saved) {
    return PHP_INT_MAX - 1;
  }

  public function buildSavedQueryFromRequest(AphrontRequest $request) {
    $saved = new PhabricatorSavedQuery();

    $saved->setParameter('isStable', $request->getStr('isStable'));
    $saved->setParameter('isUnstable', $request->getStr('isUnstable'));
    $saved->setParameter('isDeprecated', $request->getStr('isDeprecated'));
    $saved->setParameter('nameContains', $request->getStr('nameContains'));

    return $saved;
  }

  public function buildQueryFromSavedQuery(PhabricatorSavedQuery $saved) {
    $query = new PhabricatorConduitMethodQuery();

    $query->withIsStable($saved->getParameter('isStable'));
    $query->withIsUnstable($saved->getParameter('isUnstable'));
    $query->withIsDeprecated($saved->getParameter('isDeprecated'));
    $query->withIsInternal(false);

    $contains = $saved->getParameter('nameContains');
    if (phutil_nonempty_string($contains)) {
      $query->withNameContains($contains);
    }

    return $query;
  }

  public function buildSearchForm(
    AphrontFormView $form,
    PhabricatorSavedQuery $saved) {

    $form
      ->appendChild(
        id(new AphrontFormTextControl())
          ->setLabel(pht('Name Contains'))
          ->setName('nameContains')
          ->setValue($saved->getParameter('nameContains')));

    $is_stable = $saved->getParameter('isStable');
    $is_unstable = $saved->getParameter('isUnstable');
    $is_deprecated = $saved->getParameter('isDeprecated');
    $form
      ->appendChild(
        id(new AphrontFormCheckboxControl())
          ->setLabel('Stability')
          ->addCheckbox(
            'isStable',
            1,
            hsprintf(
              '<strong>%s</strong>: %s',
              pht('Stable Methods'),
              pht('Show established API methods with stable interfaces.')),
            $is_stable)
          ->addCheckbox(
            'isUnstable',
            1,
            hsprintf(
              '<strong>%s</strong>: %s',
              pht('Unstable Methods'),
              pht('Show new methods which are subject to change.')),
            $is_unstable)
          ->addCheckbox(
            'isDeprecated',
            1,
            hsprintf(
              '<strong>%s</strong>: %s',
              pht('Deprecated Methods'),
              pht(
                'Show old methods which will be deleted in a future '.
                'version of this software.')),
            $is_deprecated));
  }

  protected function getURI($path) {
    return '/conduit/'.$path;
  }

  protected function getBuiltinQueryNames() {
    return array(
      'modern' => pht('Modern Methods'),
      'all'    => pht('All Methods'),
    );
  }

  public function buildSavedQueryFromBuiltin($query_key) {
    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);

    switch ($query_key) {
      case 'modern':
        return $query
          ->setParameter('isStable', true)
          ->setParameter('isUnstable', true);
      case 'all':
        return $query
          ->setParameter('isStable', true)
          ->setParameter('isUnstable', true)
          ->setParameter('isDeprecated', true);
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  /**
   * @param array<ConduitAPIMethod> $methods
   * @param PhabricatorSavedQuery $query
   * @param array<PhabricatorObjectHandle> $handles
   * @return PhabricatorApplicationSearchResultView
   */
  protected function renderResultList(
    array $methods,
    PhabricatorSavedQuery $query,
    array $handles) {

    assert_instances_of($methods, ConduitAPIMethod::class);
    $viewer = $this->requireViewer();

    $app_groups = mgroup($methods, 'getApplicationName');

    $status_map = array(
      ConduitAPIMethod::METHOD_STATUS_UNSTABLE => array(
        'icon' => 'fa-warning',
        'label' => pht('Unstable'),
        'color' => 'yellow',
      ),
      ConduitAPIMethod::METHOD_STATUS_DEPRECATED => array(
        'icon' => 'fa-warning',
        'label' => pht('Deprecated'),
        'color' => 'red',
      ),
      ConduitAPIMethod::METHOD_STATUS_FROZEN => array(
        'icon' => 'fa-archive',
        'label' => pht('Frozen'),
        'color' => 'grey',
      ),
    );

    $lists = array();
    foreach ($app_groups as $app => $app_methods) {
      if (!$app_methods) {
        continue;
      }
      $first_method = head($app_methods);
      $app_name = $first_method->getApplicationName();

      $app_header = id(new PHUIHeaderView())
        ->setHeader($app_name);
      $list = id(new PHUIObjectItemListView())
        ->setViewer($viewer);

      foreach ($app_methods as $method) {
        $method_name = $method->getAPIMethodName();

        $summary = new PHUIRemarkupView($viewer, $method->getMethodSummary());

        $item = id(new PHUIObjectItemView())
          ->setHeader($method_name)
          ->setHref($this->getApplicationURI('method/'.$method_name.'/'))
          ->addAttribute($summary);

        $status = $method->getMethodStatus();
        if (isset($status_map[$status])) {
          $status_info = $status_map[$status];

          $item->addIcon($status_info['icon'], $status_info['label']);
          $item->setStatusIcon($status_info['icon'].' '.$status_info['color']);
        }

        $list->addItem($item);

      }
      $lists[] = id(new PHUIObjectBoxView())
        ->setHeader($app_header)
        ->setObjectList($list);
    }

    return id(new PhabricatorApplicationSearchResultView())
      ->setContent($lists);
  }

}
