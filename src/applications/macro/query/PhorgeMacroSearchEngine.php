<?php

final class PhorgeMacroSearchEngine
  extends PhorgeApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Macros');
  }

  public function getApplicationClassName() {
    return 'PhorgeMacroApplication';
  }

  public function newQuery() {
    return id(new PhorgeMacroQuery())
      ->needFiles(true);
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhorgeSearchSelectField())
        ->setLabel(pht('Status'))
        ->setKey('status')
        ->setOptions(PhorgeMacroQuery::getStatusOptions()),
      id(new PhorgeUsersSearchField())
        ->setLabel(pht('Authors'))
        ->setKey('authorPHIDs')
        ->setAliases(array('author', 'authors')),
      id(new PhorgeSearchTextField())
        ->setLabel(pht('Name Contains'))
        ->setKey('nameLike'),
      id(new PhorgeSearchStringListField())
        ->setLabel(pht('Exact Names'))
        ->setKey('names'),
      id(new PhorgeSearchSelectField())
        ->setLabel(pht('Marked with Flag'))
        ->setKey('flagColor')
        ->setDefault('-1')
        ->setOptions(PhorgeMacroQuery::getFlagColorsOptions()),
      id(new PhorgeSearchDateField())
        ->setLabel(pht('Created After'))
        ->setKey('createdStart'),
      id(new PhorgeSearchDateField())
        ->setLabel(pht('Created Before'))
        ->setKey('createdEnd'),
    );
  }

  protected function getDefaultFieldOrder() {
    return array(
      '...',
      'createdStart',
      'createdEnd',
    );
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['authorPHIDs']) {
      $query->withAuthorPHIDs($map['authorPHIDs']);
    }

    if ($map['status']) {
      $query->withStatus($map['status']);
    }

    if ($map['names']) {
      $query->withNames($map['names']);
    }

    if (strlen($map['nameLike'])) {
      $query->withNameLike($map['nameLike']);
    }

    if ($map['createdStart']) {
      $query->withDateCreatedAfter($map['createdStart']);
    }

    if ($map['createdEnd']) {
      $query->withDateCreatedBefore($map['createdEnd']);
    }

    if ($map['flagColor'] !== null) {
      $query->withFlagColor($map['flagColor']);
    }

    return $query;
  }

  protected function getURI($path) {
    return '/macro/'.$path;
  }

  protected function getBuiltinQueryNames() {
    $names = array(
      'active'  => pht('Active'),
      'all'     => pht('All'),
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
          'status',
          PhorgeMacroQuery::STATUS_ACTIVE);
      case 'all':
        return $query->setParameter(
          'status',
          PhorgeMacroQuery::STATUS_ANY);
      case 'authored':
        return $query->setParameter(
          'authorPHIDs',
          array($this->requireViewer()->getPHID()));
    }

    return parent::buildSavedQueryFromBuiltin($query_key);
  }

  protected function renderResultList(
    array $macros,
    PhorgeSavedQuery $query,
    array $handles) {

    assert_instances_of($macros, 'PhorgeFileImageMacro');
    $viewer = $this->requireViewer();
    $handles = $viewer->loadHandles(mpull($macros, 'getAuthorPHID'));

    $xform = PhorgeFileTransform::getTransformByKey(
      PhorgeFileThumbnailTransform::TRANSFORM_PINBOARD);

    $pinboard = new PHUIPinboardView();
    foreach ($macros as $macro) {
      $file = $macro->getFile();

      $item = id(new PHUIPinboardItemView())
        ->setUser($viewer)
        ->setObject($macro);

      if ($file) {
        $item->setImageURI($file->getURIForTransform($xform));
        list($x, $y) = $xform->getTransformedDimensions($file);
        $item->setImageSize($x, $y);
      }

      if ($macro->getDateCreated()) {
        $datetime = phorge_date($macro->getDateCreated(), $viewer);
        $item->appendChild(
          phutil_tag(
            'div',
            array(),
            pht('Created on %s', $datetime)));
      } else {
        // Very old macros don't have a creation date. Rendering something
        // keeps all the pins at the same height and avoids flow issues.
        $item->appendChild(
          phutil_tag(
            'div',
            array(),
            pht('Created in ages long past')));
      }

      if ($macro->getAuthorPHID()) {
        $author_handle = $handles[$macro->getAuthorPHID()];
        $item->appendChild(
          pht('Created by %s', $author_handle->renderLink()));
      }

      $item->setURI($this->getApplicationURI('/view/'.$macro->getID().'/'));
      $item->setDisabled($macro->getisDisabled());
      $item->setHeader($macro->getName());

      $pinboard->addItem($item);
    }

    $result = new PhorgeApplicationSearchResultView();
    $result->setContent($pinboard);

    return $result;
  }

  protected function getNewUserBody() {
    $create_button = id(new PHUIButtonView())
      ->setTag('a')
      ->setText(pht('Create a Macro'))
      ->setHref('/macro/create/')
      ->setColor(PHUIButtonView::GREEN);

    $icon = $this->getApplication()->getIcon();
    $app_name =  $this->getApplication()->getName();
    $view = id(new PHUIBigInfoView())
      ->setIcon($icon)
      ->setTitle(pht('Welcome to %s', $app_name))
      ->setDescription(
        pht('Create easy to remember shortcuts to images and memes.'))
      ->addAction($create_button);

      return $view;
  }

}
