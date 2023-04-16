<?php

final class PhorgePasteSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Pastes');
  }

  public function getApplicationClassName() {
    return 'PhorgePasteApplication';
  }

  public function newQuery() {
    return id(new PhorgePasteQuery())
      ->needSnippets(true);
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['authorPHIDs']) {
      $query->withAuthorPHIDs($map['authorPHIDs']);
    }

    if ($map['languages']) {
      $query->withLanguages($map['languages']);
    }

    if ($map['createdStart']) {
      $query->withDateCreatedAfter($map['createdStart']);
    }

    if ($map['createdEnd']) {
      $query->withDateCreatedBefore($map['createdEnd']);
    }

    if ($map['statuses']) {
      $query->withStatuses($map['statuses']);
    }

    return $query;
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhorgeUsersSearchField())
        ->setAliases(array('authors'))
        ->setKey('authorPHIDs')
        ->setConduitKey('authors')
        ->setLabel(pht('Authors'))
        ->setDescription(
          pht('Search for pastes with specific authors.')),
      id(new PhorgeSearchStringListField())
        ->setKey('languages')
        ->setLabel(pht('Languages'))
        ->setDescription(
          pht('Search for pastes highlighted in specific languages.')),
      id(new PhorgeSearchDateField())
        ->setKey('createdStart')
        ->setLabel(pht('Created After'))
        ->setDescription(
          pht('Search for pastes created after a given time.')),
      id(new PhorgeSearchDateField())
        ->setKey('createdEnd')
        ->setLabel(pht('Created Before'))
        ->setDescription(
          pht('Search for pastes created before a given time.')),
      id(new PhorgeSearchCheckboxesField())
        ->setKey('statuses')
        ->setLabel(pht('Status'))
        ->setDescription(
          pht('Search for archived or active pastes.'))
        ->setOptions(
          id(new PhorgePaste())
            ->getStatusNameMap()),
    );
  }

  protected function getDefaultFieldOrder() {
    return array(
      '...',
      'createdStart',
      'createdEnd',
    );
  }

  protected function getURI($path) {
    return '/paste/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'active' => pht('Active Pastes'),
      'all' => pht('All Pastes'),
    );

    if ($this->requireViewer()->isLoggedIn()) {
      $names['authored'] = pht('Authored');
    }

    return $names;
  }

  public function buildSavedQueryFromBuiltin($query_key) {

    $query = $this->newSavedQuery();
    $query->setQueryKey($query_key);

    switch ($query_key) {
      case 'active':
        return $query->setParameter(
          'statuses',
          array(
            PhorgePaste::STATUS_ACTIVE,
          ));
      case 'all':
        return $query;
      case 'authored':
        return $query->setParameter(
          'authorPHIDs',
          array($this->requireViewer()->getPHID()));
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function getRequiredHandlePHIDsForResultList(
    array $pastes,
    PhorgeSavedQuery $query) {
    return mpull($pastes, 'getAuthorPHID');
  }

  protected function renderResultList(
    array $pastes,
    PhorgeSavedQuery $query,
    array $handles) {
    assert_instances_of($pastes, 'PhorgePaste');

    $viewer = $this->requireViewer();

    $lang_map = PhorgeEnv::getEnvConfig('pygments.dropdown-choices');

    $list = new PHUIObjectItemListView();
    $list->setUser($viewer);
    foreach ($pastes as $paste) {
      $created = phorge_date($paste->getDateCreated(), $viewer);
      $author = $handles[$paste->getAuthorPHID()]->renderLink();

      $snippet_type = $paste->getSnippet()->getType();
      $lines = phutil_split_lines($paste->getSnippet()->getContent());

      $preview = id(new PhorgeSourceCodeView())
        ->setLines($lines)
        ->setTruncatedFirstBytes(
          $snippet_type == PhorgePasteSnippet::FIRST_BYTES)
        ->setTruncatedFirstLines(
          $snippet_type == PhorgePasteSnippet::FIRST_LINES)
        ->setURI(new PhutilURI($paste->getURI()));

      $source_code = phutil_tag(
        'div',
        array(
          'class' => 'phorge-source-code-summary',
        ),
        $preview);

      $created = phorge_datetime($paste->getDateCreated(), $viewer);
      $line_count = $paste->getSnippet()->getContentLineCount();
      $line_count = pht(
        '%s Line(s)',
        new PhutilNumber($line_count));

      $title = nonempty($paste->getTitle(), pht('(An Untitled Masterwork)'));

      $item = id(new PHUIObjectItemView())
        ->setObjectName('P'.$paste->getID())
        ->setHeader($title)
        ->setHref('/P'.$paste->getID())
        ->setObject($paste)
        ->addByline(pht('Author: %s', $author))
        ->addIcon('none', $created)
        ->addIcon('none', $line_count)
        ->appendChild($source_code);

      if ($paste->isArchived()) {
        $item->setDisabled(true);
      }

      $lang_name = $paste->getLanguage();
      if ($lang_name) {
        $lang_name = idx($lang_map, $lang_name, $lang_name);
        $item->addIcon('none', $lang_name);
      }

      $list->addItem($item);
    }

    $result = new PhorgeApplicationSearchResultView();
    $result->setObjectList($list);
    $result->setNoDataString(pht('No pastes found.'));

    return $result;
  }

  protected function getNewUserBody() {
    $viewer = $this->requireViewer();

    $create_button = id(new PhorgePasteEditEngine())
      ->setViewer($viewer)
      ->newNUXButton(pht('Create a Paste'));

    $icon = $this->getApplication()->getIcon();
    $app_name =  $this->getApplication()->getName();
    $view = id(new PHUIBigInfoView())
      ->setIcon($icon)
      ->setTitle(pht('Welcome to %s', $app_name))
      ->setDescription(
        pht('Store, share, and embed snippets of code.'))
      ->addAction($create_button);

      return $view;
  }
}
