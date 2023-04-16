<?php

final class PhrictionDocumentSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Wiki Documents');
  }

  public function getApplicationClassName() {
    return 'PhorgePhrictionApplication';
  }

  public function newQuery() {
    return id(new PhrictionDocumentQuery())
      ->needContent(true);
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['statuses']) {
      $query->withStatuses($map['statuses']);
    }

    if ($map['paths']) {
      $query->withSlugs($map['paths']);
    }

    if ($map['parentPaths']) {
      $query->withParentPaths($map['parentPaths']);
    }

    if ($map['ancestorPaths']) {
      $query->withAncestorPaths($map['ancestorPaths']);
    }

    return $query;
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhorgeSearchCheckboxesField())
        ->setKey('statuses')
        ->setLabel(pht('Status'))
        ->setOptions(PhrictionDocumentStatus::getStatusMap()),
      id(new PhorgeSearchStringListField())
        ->setKey('paths')
        ->setIsHidden(true)
        ->setLabel(pht('Paths')),
      id(new PhorgeSearchStringListField())
        ->setKey('parentPaths')
        ->setIsHidden(true)
        ->setLabel(pht('Parent Paths')),
      id(new PhorgeSearchStringListField())
        ->setKey('ancestorPaths')
        ->setIsHidden(true)
        ->setLabel(pht('Ancestor Paths')),
    );
  }

  protected function getURI($path) {
    return '/phriction/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'active' => pht('Active'),
      'all' => pht('All'),
    );

    return $names;
  }

  public function buildSavedQueryFromBuiltin($query_key) {

    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);

    switch ($query_key) {
      case 'all':
        return $query;
      case 'active':
        return $query->setParameter(
          'statuses',
          array(
            PhrictionDocumentStatus::STATUS_EXISTS,
          ));
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function getRequiredHandlePHIDsForResultList(
    array $documents,
    PhorgeSavedQuery $query) {

    $phids = array();
    foreach ($documents as $document) {
      $content = $document->getContent();
      $phids[] = $content->getAuthorPHID();
    }

    return $phids;
  }


  protected function renderResultList(
    array $documents,
    PhorgeSavedQuery $query,
    array $handles) {
    assert_instances_of($documents, 'PhrictionDocument');

    $viewer = $this->requireViewer();

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($documents as $document) {
      $content = $document->getContent();
      $slug = $document->getSlug();
      $author_phid = $content->getAuthorPHID();
      $slug_uri = PhrictionDocument::getSlugURI($slug);

      $byline = pht(
        'Edited by %s',
        $handles[$author_phid]->renderLink());

      $updated = phorge_datetime(
        $content->getDateCreated(),
        $viewer);

      $item = id(new PHUIObjectItemView())
        ->setHeader($content->getTitle())
        ->setObject($document)
        ->setHref($slug_uri)
        ->addByline($byline)
        ->addIcon('none', $updated);

      $item->addAttribute($slug_uri);

      $icon = $document->getStatusIcon();
      $color = $document->getStatusColor();
      $label = $document->getStatusDisplayName();

      $item->setStatusIcon("{$icon} {$color}", $label);

      if (!$document->isActive()) {
        $item->setDisabled(true);
      }

      $list->addItem($item);
    }

    $result = new PhorgeApplicationSearchResultView();
    $result->setObjectList($list);
    $result->setNoDataString(pht('No documents found.'));

    return $result;
  }

}
