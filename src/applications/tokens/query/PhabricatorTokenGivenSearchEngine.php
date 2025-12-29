<?php

final class PhabricatorTokenGivenSearchEngine
  extends PhabricatorApplicationSearchEngine {

  public function getResultTypeDescription() {
    return pht('Tokens Given');
  }

  public function getApplicationClassName() {
    return PhabricatorTokensApplication::class;
  }

  public function newQuery() {
    return new PhabricatorTokenGivenQuery();
  }

  protected function buildCustomSearchFields() {
    return array(
      id(new PhabricatorTokenSearchField())
        ->setLabel(pht('Token used'))
        ->setKey('tokenPHIDs')
        ->setConduitKey('tokens')
        ->setAliases(array('token', 'tokens')),
    );
  }

  protected function buildQueryFromParameters(array $map) {
    $query = $this->newQuery();

    if ($map['tokenPHIDs']) {
      $query->withTokenPHIDs($map['tokenPHIDs']);
    }

    return $query;
  }

  protected function getRequiredHandlePHIDsForResultList(
    array $tokens_given,
    PhabricatorSavedQuery $query) {
    $object_phids = mpull($tokens_given, 'getObjectPHID');
    $viewer_phids = mpull($tokens_given, 'getAuthorPHID');
    return array_merge($object_phids, $viewer_phids);
  }

  protected function renderResultList(
    array $tokens_given,
    PhabricatorSavedQuery $saved,
    array $handles) {
    $viewer = $this->requireViewer();

    $tokens = array();
    if ($tokens_given) {
      $token_phids = mpull($tokens_given, 'getTokenPHID');
      $tokens = id(new PhabricatorTokenQuery())
        ->setViewer($viewer)
        ->withPHIDs($token_phids)
        ->execute();
      $tokens = mpull($tokens, null, 'getPHID');
    }

    $list = new PHUIObjectItemListView();
    foreach ($tokens_given as $token_given) {
      $handle = $handles[$token_given->getObjectPHID()];
      $token = idx($tokens, $token_given->getTokenPHID());

      $item = new PHUIObjectItemView();
      $item->setHeader($handle->getFullName());
      $item->setHref($handle->getURI());

      $item->addAttribute($token->renderIcon());

      $item->addAttribute(
        pht(
          'Given by %s on %s',
          $handles[$token_given->getAuthorPHID()]->renderLink(),
          phabricator_date($token_given->getDateCreated(), $viewer)));

      $list->addItem($item);
    }

    return id(new PhabricatorApplicationSearchResultView())
      ->setObjectList($list);
  }

  protected function getBuiltinQueryNames() {
    $names = array();
    $names['all'] = pht('All Tokens Given');

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

  protected function getURI($path) {
    return '/token/given/'.$path;
  }
}
