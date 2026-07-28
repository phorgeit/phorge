<?php

final class PhorgeNamedPolicySearchEngine
  extends PhabricatorApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Named Policies');
  }

  public function getApplicationClassName() {
    return PhabricatorPolicyApplication::class;
  }

  protected function getURI($path) {
    return '/policy/named/'.$path;
  }


  protected function getRequiredHandlePHIDsForResultList(
    array $objects,
    PhabricatorSavedQuery $query) {

    return mpull($objects, 'getEffectivePolicy');
  }


  protected function renderResultList(
    array $objects,
    PhabricatorSavedQuery $query,
    array $handles) {

    $viewer = $this->requireViewer();

    $list = id(new PHUIObjectItemListView())
      ->setViewer($viewer);

    $effective_policies = mpull(
      $objects,
      'getEffectivePolicy',
      'getEffectivePolicy');
    foreach ($effective_policies as $identifier) {
      if (!strncmp($identifier, 'PHID', 4)) {
        // remove all real phids - those are handled in `$viewer->renderHandle`.
        unset($effective_policies[$identifier]);
      }
    }
    $special_policies = id(new PhabricatorPolicyQuery())
      ->setViewer($viewer)
      ->withPHIDs($effective_policies)
      ->execute();


    foreach ($objects as $policy) {
      $item = id(new PHUIObjectItemView())
        ->setViewer($viewer)
        ->setHeader($policy->getName())
        ->setHref($policy->getHref())
        ->setObject($policy);

      $item->addAttribute(
        $this->renderApplicableTo($policy));

      $item->addByline(
        $this->renderPolicy(
          $viewer,
          $special_policies,
          $policy->getEffectivePolicy()));

      $can_edit = PhabricatorPolicyFilter::hasCapability(
        $viewer,
        $policy,
        PhabricatorPolicyCapability::CAN_EDIT);

      $edit_uri = $this->getApplicationURI(
        "/named/{$policy->getId()}/");
      $item->addAction((new PHUIListItemView())
          ->setName(pht('Edit'))
          ->setIcon('fa-edit')
          ->setDisabled(!$can_edit)
          ->setHref($edit_uri));

      $list->addItem($item);
    }

    $result = new PhabricatorApplicationSearchResultView();
    $result->setObjectList($list);
    $result->setNoDataString(pht('No objects found.'));

    return $result;
  }

  protected function buildCustomSearchFields() {
    return array();
  }

  protected function buildQueryFromParameters(array $parameters) {
    return $this->newQuery();
  }

  public function newQuery() {
    return id(new PhorgeNamedPolicyQuery());
  }


  protected function getBuiltinQueryNames() {
    return array(
      'all' => pht('All Named Policies'),
    );
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


  private function renderPolicy($viewer, $specials, $identifier) {
    if (!strncmp($identifier, 'PHID', 4)) {
      return $viewer->renderHandle($identifier);
    }

    $policy = idx($specials, $identifier);
    if ($policy) {
      return $policy->getFullName();
    }

    return $identifier;
  }

  private function renderApplicableTo($policy) {
    $applicable_to = $policy->getReferenceObjectPHIDType();
    if (!$applicable_to) {
      return pht('All object types');
    } else {
      return id(new PHUIIconView())
        ->setIcon($applicable_to->getTypeIcon())
        ->setText($applicable_to->getTypeName());
    }
  }

}
