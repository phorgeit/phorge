<?php

final class PhorgeBadgesSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Badges');
  }

  public function getApplicationClassName() {
    return 'PhorgeBadgesApplication';
  }

  public function newQuery() {
    return new PhorgeBadgesQuery();
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhorgeSearchTextField())
        ->setLabel(pht('Name Contains'))
        ->setKey('name')
        ->setDescription(pht('Search for badges by name substring.')),
      id(new PhorgeSearchCheckboxesField())
        ->setKey('qualities')
        ->setLabel(pht('Quality'))
        ->setEnableForConduit(false)
        ->setOptions(PhorgeBadgesQuality::getDropdownQualityMap()),
      id(new PhorgeSearchCheckboxesField())
        ->setKey('statuses')
        ->setLabel(pht('Status'))
        ->setOptions(
          id(new PhorgeBadgesBadge())
            ->getStatusNameMap()),
    );
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['statuses']) {
      $query->withStatuses($map['statuses']);
    }

    if ($map['qualities']) {
      $query->withQualities($map['qualities']);
    }

    if ($map['name'] !== null) {
      $query->withNameNgrams($map['name']);
    }

    return $query;
  }

  protected function getURI($path) {
    return '/badges/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array();

    $names['open'] = pht('Active Badges');
    $names['all'] = pht('All Badges');

    return $names;
  }

  public function buildSavedQueryFromBuiltin($query_key) {
    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);

    switch ($query_key) {
      case 'all':
        return $query;
      case 'open':
        return $query->setParameter(
          'statuses',
          array(
            PhorgeBadgesBadge::STATUS_ACTIVE,
          ));
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function getRequiredHandlePHIDsForResultList(
    array $badges,
    PhorgeSavedQuery $query) {

    $phids = array();

    return $phids;
  }

  protected function renderResultList(
    array $badges,
    PhorgeSavedQuery $query,
    array $handles) {
    assert_instances_of($badges, 'PhorgeBadgesBadge');

    $viewer = $this->requireViewer();

    $list = id(new PHUIObjectItemListView());
    foreach ($badges as $badge) {
      $quality_name = PhorgeBadgesQuality::getQualityName(
        $badge->getQuality());

      $mini_badge = id(new PHUIBadgeMiniView())
        ->setHeader($badge->getName())
        ->setIcon($badge->getIcon())
        ->setQuality($badge->getQuality());

      $item = id(new PHUIObjectItemView())
        ->setHeader($badge->getName())
        ->setBadge($mini_badge)
        ->setHref('/badges/view/'.$badge->getID().'/')
        ->addAttribute($quality_name)
        ->addAttribute($badge->getFlavor());

      if ($badge->isArchived()) {
        $item->setDisabled(true);
        $item->addIcon('fa-ban', pht('Archived'));
      }

      $list->addItem($item);
    }

    $result = new PhorgeApplicationSearchResultView();
    $result->setObjectList($list);
    $result->setNoDataString(pht('No badges found.'));

    return $result;

  }

  protected function getNewUserBody() {
    $create_button = id(new PHUIButtonView())
      ->setTag('a')
      ->setText(pht('Create a Badge'))
      ->setHref('/badges/create/')
      ->setColor(PHUIButtonView::GREEN);

    $icon = $this->getApplication()->getIcon();
    $app_name =  $this->getApplication()->getName();
    $view = id(new PHUIBigInfoView())
      ->setIcon($icon)
      ->setTitle(pht('Welcome to %s', $app_name))
      ->setDescription(
        pht('Badges let you award and distinguish special users '.
          'throughout your install.'))
      ->addAction($create_button);

      return $view;
  }

}
